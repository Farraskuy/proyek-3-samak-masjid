<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JadwalKegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KegiatanCrudTest extends TestCase
{
    // We don't use RefreshDatabase here to avoid wiping the seeded data (roles/permissions)
    // Instead we'll manually clean up the created event

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Find the Humas user seeded in UsersSeeder
        $this->user = User::where('email', 'humas@samak.com')->first();

        if (!$this->user) {
            $this->markTestSkipped('Humas user not found. Please run seeders first.');
        }
    }

    public function test_can_create_kegiatan()
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('poster.jpg');

        $response = $this->actingAs($this->user)
            ->post(route('admin.kegiatan.store'), [
                'event_name' => 'Test Kegiatan Baru',
                'theme' => 'Tema Test',
                'location' => 'Masjid Test',
                'start_time' => now()->addDay()->format('Y-m-d\TH:i'),
                'end_time' => now()->addDay()->addHour()->format('Y-m-d\TH:i'),
                'poster' => $file,
                'daftar_tamu' => ['Ustadz A', 'Ustadz B'],
                'has_registration_form' => 0,
                'has_closing_form' => 0,
                'has_pj' => 0,
            ]);

        $response->assertRedirect(route('admin.kegiatan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'event_name' => 'Test Kegiatan Baru',
            'location' => 'Masjid Test',
        ]);

        // Verify file storage
        $event = JadwalKegiatan::where('event_name', 'Test Kegiatan Baru')->first();
        $this->assertNotNull($event->poster);
        Storage::disk('public')->assertExists($event->poster);

        // Verify guests
        $this->assertDatabaseHas('event_tamu', [
            'event_id' => $event->event_id,
            'nama_tamu' => 'Ustadz A'
        ]);

        return $event->event_id;
    }

    /**
     * @depends test_can_create_kegiatan
     */
    public function test_can_read_kegiatan($eventId)
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.kegiatan.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Kegiatan Baru');
    }

    /**
     * @depends test_can_create_kegiatan
     */
    public function test_can_update_kegiatan($eventId)
    {
        $response = $this->actingAs($this->user)
            ->put(route('admin.kegiatan.update', $eventId), [
                'event_name' => 'Test Kegiatan Updated',
                'theme' => 'Tema Updated',
                'location' => 'Masjid Updated',
                'start_time' => now()->addDays(2)->format('Y-m-d\TH:i'),
                'end_time' => now()->addDays(2)->addHour()->format('Y-m-d\TH:i'),
                'daftar_tamu' => ['Ustadz C'], // Replace guests
                'has_registration_form' => 0,
                'has_closing_form' => 0,
                'has_pj' => 0,
            ]);

        $response->assertRedirect(route('admin.kegiatan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'event_id' => $eventId,
            'event_name' => 'Test Kegiatan Updated',
            'location' => 'Masjid Updated',
        ]);

        // Verify guests replaced
        $this->assertDatabaseMissing('event_tamu', [
            'event_id' => $eventId,
            'nama_tamu' => 'Ustadz A'
        ]);
        $this->assertDatabaseHas('event_tamu', [
            'event_id' => $eventId,
            'nama_tamu' => 'Ustadz C'
        ]);
    }

    /**
     * @depends test_can_create_kegiatan
     */
    public function test_can_delete_kegiatan($eventId)
    {
        $response = $this->actingAs($this->user)
            ->delete(route('admin.kegiatan.destroy', $eventId));

        $response->assertRedirect(route('admin.kegiatan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('events', [
            'event_id' => $eventId,
        ]);
    }
}
