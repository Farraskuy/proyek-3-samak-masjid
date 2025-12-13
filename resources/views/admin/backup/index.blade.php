@extends('admin.layout')

@section('title', 'Backup Database')

@section('content')
    <section class="p-3 container">

        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm rounded-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-semibold mb-0">Backup & Restore Database</h4>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Create Backup --}}
                <form action="{{ route('admin.backup.store') }}" method="POST">
                    @csrf
                    <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                        <h5 class="fw-semibold mb-3"><i class="fas fa-database me-2 text-primary"></i>Buat Backup Manual</h5>
                        
                        {{-- Table Selection --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Tabel <span class="text-danger">*</span></label>
                            <div class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-3 me-1" id="selectAll">
                                    <i class="fas fa-check-double me-1"></i>Pilih Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" id="deselectAll">
                                    <i class="fas fa-times me-1"></i>Batal Pilih
                                </button>
                            </div>
                            <div class="border rounded-3 p-3" style="max-height: 200px; overflow-y: auto; background: #fafafa;">
                                <div class="row g-2">
                                    @foreach($tables as $table)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input table-checkbox" type="checkbox" 
                                                    name="tables[]" value="{{ $table }}" id="table_{{ $table }}">
                                                <label class="form-check-label small" for="table_{{ $table }}">
                                                    {{ $table }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Storage Type --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Simpan Ke</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="storage_type" 
                                        value="local" id="storage_local" checked>
                                    <label class="form-check-label" for="storage_local">
                                        <i class="fas fa-server me-1 text-muted"></i> Storage Server
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="storage_type" 
                                        value="download" id="storage_download">
                                    <label class="form-check-label" for="storage_download">
                                        <i class="fas fa-download me-1 text-muted"></i> Download Langsung
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-3 py-2 fw-semibold">
                            <i class="fas fa-save me-2"></i>Backup Database
                        </button>
                    </div>
                </form>

                {{-- Scheduler Section --}}
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0"><i class="fas fa-clock me-2 text-warning"></i>Jadwal Backup Otomatis</h5>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="scheduleToggle"
                                {{ $schedule->is_enabled ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="scheduleToggle">
                                {{ $schedule->is_enabled ? 'Aktif' : 'Nonaktif' }}
                            </label>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.backup.schedule.update') }}" method="POST" id="scheduleForm">
                        @csrf
                        <input type="hidden" name="is_enabled" id="isEnabledInput" value="{{ $schedule->is_enabled ? '1' : '0' }}">
                        
                        <div id="scheduleSettings" style="display: {{ $schedule->is_enabled ? 'block' : 'none' }};">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Frekuensi</label>
                                    <select name="frequency" class="form-select rounded-3">
                                        <option value="daily" {{ $schedule->frequency == 'daily' ? 'selected' : '' }}>Harian</option>
                                        <option value="weekly" {{ $schedule->frequency == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                        <option value="monthly" {{ $schedule->frequency == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="yearly" {{ $schedule->frequency == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Format Output</label>
                                    <select name="output_format" class="form-select rounded-3">
                                        <option value="single" {{ $schedule->output_format == 'single' ? 'selected' : '' }}>Single SQL File</option>
                                        <option value="zip" {{ $schedule->output_format == 'zip' ? 'selected' : '' }}>ZIP (Per Tabel)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tabel untuk Backup Terjadwal</label>
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-3 ms-2" data-bs-toggle="modal" data-bs-target="#tableSelectModal">
                                    <i class="fas fa-table me-1"></i>Pilih Tabel ({{ count($schedule->tables ?? []) }} dipilih)
                                </button>
                                
                                {{-- Hidden inputs for selected tables --}}
                                <div id="selectedTablesContainer">
                                    @foreach($schedule->tables ?? [] as $table)
                                        <input type="hidden" name="schedule_tables[]" value="{{ $table }}">
                                    @endforeach
                                </div>
                                
                                @if(count($schedule->tables ?? []) > 0)
                                    <div class="mt-2">
                                        <small class="text-muted">Tabel terpilih: {{ implode(', ', $schedule->tables ?? []) }}</small>
                                    </div>
                                @endif
                            </div>
                            
                            @if($schedule->next_run_at)
                                <div class="alert alert-info small py-2 mb-3">
                                    <i class="fas fa-calendar me-1"></i>
                                    Backup berikutnya: <strong>{{ $schedule->next_run_at->format('d M Y H:i') }}</strong>
                                </div>
                            @endif
                            
                            <button type="submit" class="btn btn-warning rounded-3 fw-semibold">
                                <i class="fas fa-save me-2"></i>Simpan Jadwal
                            </button>
                        </div>
                        
                        <div id="scheduleDisabledMsg" style="display: {{ $schedule->is_enabled ? 'none' : 'block' }};">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Aktifkan untuk mengatur jadwal backup otomatis.
                            </p>
                        </div>
                    </form>
                </div>

                {{-- Import Backup --}}
                <form action="{{ route('admin.backup.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card bg-white border-0 rounded-3 p-4">
                        <h5 class="fw-semibold mb-3"><i class="fas fa-upload me-2 text-success"></i>Import Backup</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File Backup (.sql)</label>
                            <input type="file" class="form-control rounded-3" name="backup_file" accept=".sql,.txt" required>
                            <small class="text-muted">Format: .sql atau .txt | Max: 50MB</small>
                        </div>
                        
                        <button type="submit" class="btn btn-success rounded-3 py-2 fw-semibold">
                            <i class="fas fa-upload me-2"></i>Import File
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right: Backup List --}}
            <div class="col-lg-4">
                <div class="card bg-white border-0 rounded-3 p-4 mb-4">
                    <h5 class="fw-semibold mb-3">Backup Tersimpan ({{ count($backups) }})</h5>
                    
                    <div style="max-height: 350px; overflow-y: auto;">
                        @forelse($backups as $backup)
                            <div class="p-3 mb-2 rounded-3" style="background: #f8f9fa;">
                                <p class="mb-1 fw-medium small">{{ $backup['name'] }}</p>
                                <p class="mb-2 text-muted" style="font-size: 12px;">
                                    <i class="fas fa-weight me-1"></i>{{ $backup['size'] }} 
                                    <span class="mx-1">•</span>
                                    <i class="fas fa-clock me-1"></i>{{ $backup['date'] }}
                                </p>
                                <div class="d-flex gap-2">
                                    {{-- Download --}}
                                    <a href="{{ route('admin.backup.download', $backup['name']) }}" 
                                       class="btn btn-sm btn-success rounded-3" title="Download">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                    {{-- Restore --}}
                                    <form action="{{ route('admin.backup.restore', $backup['name']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('PERINGATAN: Restore akan menjalankan semua SQL dalam file ini. Data mungkin tertimpa. Lanjutkan?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning rounded-3" title="Restore">
                                            <i class="fas fa-undo me-1"></i>Restore
                                        </button>
                                    </form>
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.backup.destroy', $backup['name']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus backup ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-database fa-2x text-muted mb-2"></i>
                                <p class="text-muted small mb-0">Belum ada backup tersimpan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="card bg-white border-0 rounded-3 p-4">
                    <h6 class="fw-semibold mb-2"><i class="fas fa-info-circle me-1 text-info"></i>Informasi</h6>
                    <ul class="mb-0 ps-3 small text-muted">
                        <li><strong>Backup Manual</strong>: Buat backup instan</li>
                        <li><strong>Jadwal</strong>: Backup otomatis berkala</li>
                        <li><strong>Import</strong>: Upload file SQL ke server</li>
                        <li><strong>Restore</strong>: Jalankan SQL dari backup</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal for Table Selection --}}
    <div class="modal fade" id="tableSelectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold"><i class="fas fa-table me-2"></i>Pilih Tabel untuk Jadwal Backup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 me-1" id="modalSelectAll">Pilih Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" id="modalDeselectAll">Batal Pilih</button>
                    </div>
                    <div class="border rounded-3 p-3" style="max-height: 300px; overflow-y: auto; background: #fafafa;">
                        <div class="row g-2">
                            @foreach($tables as $table)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input modal-table-checkbox" type="checkbox" 
                                            value="{{ $table }}" id="modal_{{ $table }}"
                                            {{ in_array($table, $schedule->tables ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="modal_{{ $table }}">
                                            {{ $table }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-3" id="confirmTableSelection">
                        <i class="fas fa-check me-1"></i>Konfirmasi Pilihan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Manual backup table selection
        document.getElementById('selectAll').addEventListener('click', function() {
            document.querySelectorAll('.table-checkbox').forEach(cb => cb.checked = true);
        });
        document.getElementById('deselectAll').addEventListener('click', function() {
            document.querySelectorAll('.table-checkbox').forEach(cb => cb.checked = false);
        });
        
        // Schedule toggle
        const scheduleToggle = document.getElementById('scheduleToggle');
        const scheduleSettings = document.getElementById('scheduleSettings');
        const scheduleDisabledMsg = document.getElementById('scheduleDisabledMsg');
        const isEnabledInput = document.getElementById('isEnabledInput');
        const toggleLabel = scheduleToggle.nextElementSibling;
        
        scheduleToggle.addEventListener('change', function() {
            if (this.checked) {
                scheduleSettings.style.display = 'block';
                scheduleDisabledMsg.style.display = 'none';
                isEnabledInput.value = '1';
                toggleLabel.textContent = 'Aktif';
            } else {
                scheduleSettings.style.display = 'none';
                scheduleDisabledMsg.style.display = 'block';
                isEnabledInput.value = '0';
                toggleLabel.textContent = 'Nonaktif';
            }
        });
        
        // Modal table selection
        document.getElementById('modalSelectAll').addEventListener('click', function() {
            document.querySelectorAll('.modal-table-checkbox').forEach(cb => cb.checked = true);
        });
        document.getElementById('modalDeselectAll').addEventListener('click', function() {
            document.querySelectorAll('.modal-table-checkbox').forEach(cb => cb.checked = false);
        });
        
        // Confirm table selection from modal
        document.getElementById('confirmTableSelection').addEventListener('click', function() {
            const container = document.getElementById('selectedTablesContainer');
            container.innerHTML = '';
            
            const selectedTables = [];
            document.querySelectorAll('.modal-table-checkbox:checked').forEach(cb => {
                selectedTables.push(cb.value);
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'schedule_tables[]';
                input.value = cb.value;
                container.appendChild(input);
            });
            
            // Update button text
            const btn = document.querySelector('[data-bs-target="#tableSelectModal"]');
            btn.innerHTML = `<i class="fas fa-table me-1"></i>Pilih Tabel (${selectedTables.length} dipilih)`;
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('tableSelectModal')).hide();
        });
    </script>
    @endpush
@endsection
