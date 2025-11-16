<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Postingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostinganController extends Controller
{
    // Show listing page (previously HalamanPostinganController::return_resource)
    public function index()
    {
        $data_posts = Postingan::all();
        return view('post.halaman_postingan', ['data_posts' => $data_posts]);
    }

    // Show detail page by slug (previously DetailPostinganController::return_resource)
    public function showDetail($slug)
    {
        $data_posts = DB::table('posts')->select('content')->where('slug', $slug)->first();

        $kontent_html_tag = $data_posts->content;

        $obj_html = new \DOMDocument();
        libxml_use_internal_errors(true);
        $obj_html->loadHTML($kontent_html_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $img = $obj_html->getElementsByTagName("img");

        foreach ($img as $image_tag) {
            $src = $image_tag->getAttribute('src');
            if (!str_starts_with($src, '/storage/')) {
                $image_tag->setAttribute('src', '/storage/' . $src);
            }
        }

        $updated_html = $obj_html->saveHTML();

        return view('post.fitur_detail_postingan', ['data_posts' => $updated_html]);
    }

    // Return add-article form (previously AddPostinganController::return_resource)
    public function create()
    {
        session()->flash('token_tambah_artikel', 199);
        return view('tambah_artikel');
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

        DB::table('posts')->insert([
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

        return redirect()->to('/admin/artikel')->with('success_post_disimpan_di_database', 'Data berhasil disimpan!');
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
    public function getEditArtikel()
    {
        $post = DB::table('posts')->select('title', 'status', 'kategori', 'slug', 'post_id')->get();
        return view('edit_artikel')->with('post_data', $post);
    }

    // Delete article and associated images (previously ShowPostingan::deleteArtikel)
    public function deleteArtikel($id)
    {
        $this->search_delete_featured_image($id);
        $this->search_delete_kontent_image($id);
        DB::table('posts')->where('post_id', (int)$id)->delete();
        return redirect()->back()->with('status', 'Artikel berhasil dihapus');
    }

    // Delete featured image file
    protected function search_delete_featured_image($id)
    {
        $featured_image_fc = DB::table('posts')->select('featured_image_url')->where('post_id', (int)$id)->first();
        $path = $featured_image_fc->featured_image_url;
        Storage::disk('public')->delete($path);
    }

    // Delete images embedded in content
    protected function search_delete_kontent_image($id)
    {
        $kontent_image = DB::table('posts')->select('content')->where('post_id', (int)$id)->first();
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
}
