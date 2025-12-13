<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackupController extends Controller
{
    protected $backupPath = 'backups';

    public function __construct()
    {
        // Ensure backup directory exists
        if (!Storage::disk('local')->exists($this->backupPath)) {
            Storage::disk('local')->makeDirectory($this->backupPath);
        }
    }

    /**
     * Display backup page with table selection
     */
    public function index()
    {
        // Get all tables from database
        $tables = $this->getDatabaseTables();
        
        // Get existing backups using glob (more reliable than Storage facade)
        $backupDir = storage_path('app/' . $this->backupPath);
        
        // Ensure directory exists
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $files = glob($backupDir . '/*.sql');
        
        $backups = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'size' => $this->formatFileSize(filesize($file)),
                'date' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        })->sortByDesc('date')->values();

        // Get or create backup schedule (singleton pattern, only 1 schedule)
        $schedule = BackupSchedule::firstOrCreate(
            ['id' => 1],
            ['is_enabled' => false, 'frequency' => 'weekly', 'tables' => [], 'output_format' => 'single']
        );

        return view('admin.backup.index', compact('tables', 'backups', 'schedule'));
    }

    /**
     * Create backup with selected tables
     */
    public function store(Request $request)
    {
        $request->validate([
            'tables' => 'required|array|min:1',
            'tables.*' => 'string',
            'storage_type' => 'required|in:local,download',
        ]);

        $selectedTables = $request->input('tables');
        $storageType = $request->input('storage_type');
        
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupDir = storage_path('app/' . $this->backupPath);
            
            // Ensure backup directory exists
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filepath = $backupDir . '/' . $filename;
            
            // Generate SQL backup content
            $sqlContent = $this->generateSqlBackup($selectedTables);
            
            // Save to file
            file_put_contents($filepath, $sqlContent);
            
            \Log::info('Database backup created: ' . $filename . ' (Tables: ' . implode(', ', $selectedTables) . ')');

            if ($storageType === 'download') {
                // Return file for download
                return response()->download($filepath)->deleteFileAfterSend(false);
            }

            return back()->with('success', 'Backup berhasil dibuat: ' . $filename);

        } catch (\Exception $e) {
            \Log::error('Backup exception: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    /**
     * Download backup file
     */
    public function download($filename)
    {
        $filepath = storage_path('app/' . $this->backupPath . '/' . $filename);

        if (!file_exists($filepath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return response()->download($filepath);
    }

    /**
     * Delete backup file
     */
    public function destroy($filename)
    {
        $filepath = storage_path('app/' . $this->backupPath . '/' . $filename);

        if (!file_exists($filepath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        unlink($filepath);
        \Log::info('Backup deleted: ' . $filename);

        return back()->with('success', 'Backup berhasil dihapus.');
    }

    /**
     * Restore database from backup file (with transaction)
     */
    public function restore($filename)
    {
        $filepath = storage_path('app/' . $this->backupPath . '/' . $filename);

        if (!file_exists($filepath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        try {
            $sql = file_get_contents($filepath);
            
            // Split SQL into statements
            $statements = $this->parseSqlStatements($sql);
            
            $executed = 0;
            
            // Use transaction for atomicity
            DB::beginTransaction();
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement) || strpos($statement, '--') === 0) {
                    continue;
                }
                
                DB::unprepared($statement);
                $executed++;
            }
            
            DB::commit();
            
            \Log::info("Database restored from: {$filename}. Executed: {$executed} statements.");
            
            return back()->with('success', "Database berhasil di-restore dari {$filename}. ({$executed} statement dijalankan)");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Restore exception: ' . $e->getMessage());
            return back()->with('error', 'Gagal restore (rollback): ' . $e->getMessage());
        }
    }

    /**
     * Import backup file from upload
     */
    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:51200', // Max 50MB
        ]);

        try {
            $file = $request->file('backup_file');
            $filename = 'imported_' . date('Y-m-d_His') . '.sql';
            $backupDir = storage_path('app/' . $this->backupPath);
            
            // Ensure directory exists
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $file->move($backupDir, $filename);
            
            \Log::info('Backup imported: ' . $filename);
            
            return back()->with('success', 'File backup berhasil diimport: ' . $filename);

        } catch (\Exception $e) {
            \Log::error('Import exception: ' . $e->getMessage());
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Update backup schedule settings
     */
    public function updateSchedule(Request $request)
    {
        $request->validate([
            'is_enabled' => 'required|boolean',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'schedule_tables' => 'array',
            'schedule_tables.*' => 'string',
            'output_format' => 'required|in:single,zip',
        ]);

        $schedule = BackupSchedule::firstOrCreate(['id' => 1]);
        
        $schedule->update([
            'is_enabled' => $request->boolean('is_enabled'),
            'frequency' => $request->frequency,
            'tables' => $request->schedule_tables ?? [],
            'output_format' => $request->output_format,
            'next_run_at' => $request->boolean('is_enabled') ? $schedule->calculateNextRun() : null,
        ]);

        \Log::info('Backup schedule updated: ' . ($schedule->is_enabled ? 'Enabled' : 'Disabled') . ' - ' . $schedule->frequency);

        return back()->with('success', 'Jadwal backup berhasil diperbarui.');
    }

    /**
     * Parse SQL file into individual statements
     */
    protected function parseSqlStatements(string $sql): array
    {
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        
        // Split by semicolon but handle values with semicolons
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            
            if ($inString) {
                $current .= $char;
                if ($char === $stringChar && ($i === 0 || $sql[$i-1] !== '\\')) {
                    $inString = false;
                }
            } else {
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                    $current .= $char;
                } elseif ($char === ';') {
                    $statements[] = trim($current);
                    $current = '';
                } else {
                    $current .= $char;
                }
            }
        }
        
        if (trim($current)) {
            $statements[] = trim($current);
        }
        
        return array_filter($statements);
    }

    /**
     * Get all database tables
     */
    protected function getDatabaseTables(): array
    {
        $tables = [];
        $connection = config('database.default');
        
        if ($connection === 'pgsql') {
            $result = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            foreach ($result as $row) {
                $tables[] = $row->tablename;
            }
        } else {
            // MySQL / SQLite
            $result = DB::select('SHOW TABLES');
            foreach ($result as $row) {
                $tables[] = array_values((array) $row)[0];
            }
        }
        
        sort($tables);
        return $tables;
    }

    /**
     * Generate SQL backup for selected tables
     */
    protected function generateSqlBackup(array $tables): string
    {
        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Tables: " . implode(', ', $tables) . "\n\n";

        foreach ($tables as $table) {
            $sql .= $this->generateTableBackup($table);
        }

        return $sql;
    }

    /**
     * Generate backup SQL for a single table
     */
    protected function generateTableBackup(string $table): string
    {
        $sql = "-- Table: {$table}\n";
        
        // Get table data
        $rows = DB::table($table)->get();
        
        if ($rows->isEmpty()) {
            $sql .= "-- No data in table {$table}\n\n";
            return $sql;
        }

        // Get columns
        $columns = array_keys((array) $rows->first());
        $columnList = '"' . implode('", "', $columns) . '"';
        
        $sql .= "-- Total rows: " . $rows->count() . "\n";
        
        foreach ($rows as $row) {
            $values = [];
            foreach ($columns as $column) {
                $value = $row->$column;
                if (is_null($value)) {
                    $values[] = 'NULL';
                } elseif (is_bool($value)) {
                    $values[] = $value ? 'TRUE' : 'FALSE';
                } elseif (is_numeric($value)) {
                    $values[] = $value;
                } else {
                    $values[] = "'" . addslashes($value) . "'";
                }
            }
            $valueList = implode(', ', $values);
            $sql .= "INSERT INTO \"{$table}\" ({$columnList}) VALUES ({$valueList});\n";
        }
        
        $sql .= "\n";
        return $sql;
    }

    /**
     * Format file size to human readable
     */
    protected function formatFileSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
