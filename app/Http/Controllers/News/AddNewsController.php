<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AddNewsController extends Controller
{
    public function upload(Request $request)
    {
        // Validasi form utama
        $validated = $request->validate([
            'title_view' => 'required|string|max:255',
            'keterangan_view' => 'required|string',
            'kategori_view' => 'required|string',
            'image_view' => 'nullable|image|max:2048', // optional
            'content_view' => 'nullable|string'
        ]);

        // Handle featured image
        $featuredImagePath = null;
        if ($request->hasFile('image_view')) {
            $image = $request->file('image_view');
            $newName = uniqid() . '_' . $image->getClientOriginalName();
            $featuredImagePath = $image->storeAs('public/news/images', $newName);
        }

        // Handle Quill content
        $content = $request->input('content_view');
        if ($content) {
            // Quill mungkin mengirimkan image sebagai base64, kita bisa ekstrak dan simpan
            $content = $this->processQuillImages($content);
        }

        // Buat slug
        $slug = Str::slug($validated['title_view']);

        // Simpan ke database
        DB::table('posts')->insert([
            'title' => $validated['title_view'],
            'slug' => $slug,
            'keterangan' => $validated['keterangan_view'],
            'featured_image_url' => $featuredImagePath, // path dari storage
            'content' => $content,
            'kategori' => $validated['kategori_view'],
            'created_at' => now(),
            'status' => 'not publish',
            'user_id' =>  1
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Proses image yang ada di Quill editor
     * Mengubah <img src="data:image/..."> menjadi path file Laravel
     */
    private function processQuillImages($content)
    {
        // Gunakan DOMDocument untuk parsing HTML
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            // Cek apakah base64
            if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                $data = substr($src, strpos($src, ',') + 1);
                $data = base64_decode($data);
                $extension = $type[1]; // png, jpg, dll
                $fileName = uniqid() . '.' . $extension;
                $path = 'public/news/quill/' . $fileName;

                Storage::put($path, $data);

                // Ganti src dengan path file Laravel
                $img->setAttribute('src', Storage::url($path));
            }
        }

        // Return HTML kembali
        return $dom->saveHTML();
    }
}
