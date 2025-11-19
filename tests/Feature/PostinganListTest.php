<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Postingan;

class PostinganListTest extends TestCase
{
    use RefreshDatabase;

    public function test_postingan_list_shows_posts()
    {
        Postingan::factory()->count(3)->create();

        $resp = $this->get('/postingan');

        $resp->assertStatus(200);
        $resp->assertSeeText(Postingan::first()->title);
    }
}
