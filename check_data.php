<?php
// Simple script to check data
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USERS ===\n";
$users = \App\Models\User::select('id', 'username', 'full_name', 'email')->get();
foreach ($users as $u) {
    echo "ID: {$u->id} - {$u->username} - {$u->full_name} ({$u->email})\n";
}

echo "\n=== EVENTS ===\n";
$events = \App\Models\JadwalKegiatan::select('event_id', 'event_name', 'pj_user_id')->get();
if ($events->count() == 0) {
    echo "No events found.\n";
} else {
    foreach ($events as $e) {
        echo "Event: {$e->event_id} - {$e->event_name} - PJ User ID: {$e->pj_user_id}\n";
    }
}
