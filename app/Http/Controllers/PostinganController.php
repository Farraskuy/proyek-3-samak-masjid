<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostinganController extends Controller
{
    // Show listing page (previously HalamanPostinganController::return_resource)
    public function index(Request $request)
    {
        $filter = $request->query('filter'); // ?filter=...
        $query = Post::query();

        if (!empty($filter)) {
            $query->where('kategori', $filter);
        }

        $data_posts = $query->orderBy('created_at', 'desc')->paginate(9)->withQueryString();

        return view('post.halaman_postingan', ['data_posts' => $data_posts]);
    }

    // Show detail page by slug (previously DetailPostinganController::return_resource)
    public function showDetail($slug_from_view)
    {

        $postRecord = Post::select('content')->where('slug', $slug_from_view)->first();

        if (!$postRecord) {
            abort(404, 'Postingan tidak ditemukan');
        }

        $kontent_html_tag = $postRecord->content;

        // BUNGKUS AGAR TIDAK DI-MERGE
        $kontent_html_tag = "<div>$kontent_html_tag</div>";

        $obj_html = new \DOMDocument();
        libxml_use_internal_errors(true);

        // load HTML wrapper
        $obj_html->loadHTML($kontent_html_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Tambah prefix /storage/
        $img = $obj_html->getElementsByTagName("img");
        foreach ($img as $image_tag) {
            $src = $image_tag->getAttribute('src');
            if (!str_starts_with($src, '/storage/')) {
                $image_tag->setAttribute('src', '/storage/' . $src);
            }
        }

        // Ambil isi dalam wrapper div
        $updated_html = $obj_html->saveHTML($obj_html->documentElement);

        // Hapus <div> pembungkus
        $updated_html = preg_replace('/^<div>|<\/div>$/', '', $updated_html);

        return view('post.fitur_detail_postingan', ['data_posts' => $updated_html]);
    }

    // Return add-article form (previously AddPostinganController::return_resource)
    public function create()
    {
        session()->flash('token_tambah_artikel', 199);
        return view('post.tambah_artikel');
    }

    // Store uploaded article (previously AddPostinganController::upload)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_view' => 'required|string|max:255',
            'keterangan_view' => 'required|string',
            'kategori_view' => 'required|string',
            'image_view' => 'nullable|image|max:2048',
            'content_view' => 'nullable|string'
        ]);

        $featuredImagePath = null;
        if ($request->hasFile('image_view')) {
            $image = $request->file('image_view');
            $newName = uniqid() . '_' . $image->getClientOriginalName();
            $featuredImagePath = $image->storeAs('news/images', $newName);
        }

        $content = $request->input('content_view');
        if ($content) {
            $content = $this->processQuillImages($content);
        }

        $slug = Str::slug($validated['title_view']);

        Post::create([
            'title' => $validated['title_view'],
            'slug' => $slug . '-' . 'DAKWAH' . uniqid(),
            'keterangan' => $validated['keterangan_view'],
            'featured_image_url' => $featuredImagePath,
            'content' => $content,
            'kategori' => $validated['kategori_view'],
            'created_at' => now(),
            'status' => 'not publish',
            'user_id' => 1
        ]);

        return redirect()->to('/admin/postingan')->with('success_post_disimpan_di_database', 'Data berhasil disimpan!');
    }

    // Process base64 images in Quill content and store them (from AddPostinganController::processQuillImages)
    private function processQuillImages($content)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                $data = substr($src, strpos($src, ',') + 1);
                $data = base64_decode($data);
                $extension = $type[1];
                $fileName = uniqid() . '.' . $extension;
                $path = 'news/quill/' . $fileName;

                Storage::put($path, $data);

                $img->setAttribute('src', $path);
            }
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        $elemen_dari_body = '';
        foreach ($body->childNodes as $isi_elemen_body) {
            $elemen_dari_body .= $dom->saveHTML($isi_elemen_body);
        }

        return $elemen_dari_body;
    }

    // Admin: list articles for edit (previously ShowPostingan::getEditArtikel)
    public function indexAdmin()
    {
        $post = Post::orderBy('created_at', 'desc')->get();
        return view('post.list_artikel_admin')->with('post_data', $post);
    }

    // Delete article and associated images (previously ShowPostingan::deleteArtikel)
    public function deleteArtikel($id)
    {
        $this->search_delete_featured_image($id);
        $this->search_delete_kontent_image($id);
        Post::where('post_id', (int)$id)->delete();
        return redirect()->back()->with('status', 'Artikel berhasil dihapus');
    }

    // Delete featured image file
    protected function search_delete_featured_image($id)
    {
        $featured_image_fc = Post::select('featured_image_url')->where('post_id', (int)$id)->first();
        if ($featured_image_fc && $featured_image_fc->featured_image_url) {
            $path = $featured_image_fc->featured_image_url;
            Storage::disk('public')->delete($path);
        }
    }

    // Delete images embedded in content
    protected function search_delete_kontent_image($id)
    {
        $kontent_image = Post::select('content')->where('post_id', (int)$id)->first();
        if (!$kontent_image) {
            return;
        }
        $kontent_html_tag = $kontent_image->content;

        $obj_html = new \DOMDocument();
        libxml_use_internal_errors(true);
        $obj_html->loadHTML($kontent_html_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $img = $obj_html->getElementsByTagName("img");

        foreach ($img as $image_tag) {
            $src = $image_tag->getAttribute('src');
            Storage::disk('public')->delete($src);
        }
    }

    public function edit($id)
    {
        $post = Post::where('post_id', $id)->firstOrFail();

        // Tambahkan /storage/ hanya untuk tag <img>
        $post->content = preg_replace(
            '/<img\s+[^>]*src="(news\/[^"]+)"/i',
            '<img src="/storage/$1"',
            $post->content
        );

        return view('post.edit_postingan_admin', compact('post'));
    }





    public function update(Request $request, $id)
    {
        // 1. Ambil data post yang ada
        $post = Post::where('post_id', $id)->firstOrFail();

        // 2. Validasi input
        $validated = $request->validate([
            'title_view' => 'required|string|max:255',
            'keterangan_view' => 'required|string',
            'kategori_view' => 'required|string',
            'image_view' => 'nullable|image|max:2048', // Boleh null jika tidak ganti gambar
            'content_view' => 'nullable|string'
        ]);

        // 3. Handle Gambar Header (Featured Image)
        $featuredImagePath = $post->featured_image_url; // Default pakai gambar lama

        if ($request->hasFile('image_view')) {
            // Jika ada gambar baru di-upload:

            // Hapus gambar lama (jika ada)
            if ($post->featured_image_url) {
                Storage::delete($post->featured_image_url);
            }

            // Simpan gambar baru
            $image = $request->file('image_view');
            $newName = uniqid() . '_' . $image->getClientOriginalName();
            $featuredImagePath = $image->storeAs('public/news/images', $newName);
        }

        // 4. Handle Konten Quill (termasuk gambar base64 baru)
        $content = $request->input('content_view');

        if ($content) {

            // Hapus prefix /storage/ agar database tetap bersih
            $content = preg_replace(
                '/src="\/storage\/(news\/[^"]+)"/i',
                'src="$1"',
                $content
            );

            // Proses gambar base64 baru
            $content = $this->processQuillImages($content);
        }


        // 5. Handle Slug (buat baru jika judul berubah)
        $slug = $post->slug;
        if ($post->title !== $validated['title_view']) {
            $slug = Str::slug($validated['title_view']) . '-' . 'DAKWAH' . uniqid();
        }

        // 6. Update ke database
        $post->update([
            'title' => $validated['title_view'],
            'slug' => $slug,
            'keterangan' => $validated['keterangan_view'],
            'featured_image_url' => $featuredImagePath,
            'content' => $content,
            'kategori' => $validated['kategori_view'],
            'created_at' => now() // Tambahkan updated_at
        ]);

        return redirect()->to('/admin/postingan')->with('success_post_disimpan_di_database', 'Data berhasil diupdate!');
    }
}
