<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Postingan;

class PostinganDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_postingan_detail_shows_content()
    {
        $post = Postingan::factory()->create();

        $resp = $this->get('/postingan/' . $post->slug);

        $resp->assertStatus(200);
        $resp->assertSeeText($post->title);
        $resp->assertSeeText(strip_tags($post->keterangan));
    }
}
