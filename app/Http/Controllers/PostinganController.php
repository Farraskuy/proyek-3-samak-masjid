<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Postingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $data_posts = Postingan::where('slug', $slug)->first();

        if (!$data_posts) {
            abort(404, 'Postingan tidak ditemukan');
        }

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
        return view('admin.postingan.tambah');
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

        // Daftar file yang berhasil disimpan, dipakai untuk rollback jika error
        $savedFiles = [];

        DB::beginTransaction();

        try {
            // =============== 1. Upload Featured Image (jika ada) ===============
            $featuredImagePath = null;

            if ($request->hasFile('image_view')) {
                $image = $request->file('image_view');

                $newName = uniqid() . '_' . $image->getClientOriginalName();
                $featuredImagePath = $image->storeAs('news/images', $newName);

                // Catat file untuk rollback jika error
                $savedFiles[] = $featuredImagePath;
            }

            // =============== 2. Process Quill HTML Images ===============
            $content = $request->input('content_view');

            if ($content) {
                $content = $this->processQuillImages($content);
                $savedFiles = array_merge($savedFiles, $content['savedFiles']);
            }

            // =============== 3. Generate Slug ===============
            $slug = Str::slug($validated['title_view']) . '-DAKWAH' . uniqid();

            // =============== 4. Insert Database ===============
            Postingan::create([
                'title' => $validated['title_view'],
                'slug' => $slug,
                'keterangan' => $validated['keterangan_view'],
                'featured_image_url' => $featuredImagePath,
                'content' => $content,
                'kategori' => $validated['kategori_view'],
                'created_at' => now(),
                'status' => $request->input('status') != 'draff' ? 'pending' : 'draft',
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->to('/admin/artikel')
                ->with('success_post_disimpan_di_database', 'Data berhasil disimpan!');
        } catch (\Throwable $e) {

            DB::rollBack();

            // =============== 5. Hapus semua file yang terlanjur disimpan ===============
            foreach ($savedFiles as $file) {
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
            }

            // Debugging
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Process base64 images in Quill content and store them (from AddPostinganController::processQuillImages)
    private function processQuillImages($content)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');
        $savedFiles = [];
        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {

                $data = substr($src, strpos($src, ',') + 1);
                $data = base64_decode($data);
                $extension = $type[1];
                $fileName = uniqid() . '.' . $extension;
                $path = 'news/quill/' . $fileName;

                // Simpan file
                Storage::put($path, $data);

                // Catat file untuk rollback
                $savedFiles[] = $path;

                // Ganti src menjadi path file
                $img->setAttribute('src', $path);
            }
        }

        // Ambil isi body
        $body = $dom->getElementsByTagName('body')->item(0);
        $result = '';

        foreach ($body->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        return [
            'content' => $result,
            'savedFiles' => $savedFiles
        ];
    }


    // Admin: list articles for edit (previously ShowPostingan::getEditArtikel)
    public function indexAdmin()
    {
        $perPage = request()->query('showing', 50);
        $query = Postingan::orderBy('created_at', 'desc');

        if ($perPage === 'all') {
            $post = $query->get();
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 50;
            $post = $query->paginate($perPage)->withQueryString();
        }

        return view('admin.postingan.index')->with('data', $post);
    }

    // Delete article and associated images (previously ShowPostingan::deleteArtikel)
    public function deleteArtikel($id)
    {
        $this->search_delete_featured_image($id);
        $this->search_delete_kontent_image($id);
        Postingan::where('post_id', (int)$id)->delete();
        return redirect()->back()->with('status', 'Artikel berhasil dihapus');
    }

    // Delete featured image file
    protected function search_delete_featured_image($id)
    {
        $featured_image_fc = Postingan::select('featured_image_url')->where('post_id', (int)$id)->first();
        if ($featured_image_fc && $featured_image_fc->featured_image_url) {
            Storage::disk('public')->delete($featured_image_fc->featured_image_url);
        }
    }

    // Delete images embedded in content
    protected function search_delete_kontent_image($id)
    {
        $kontent_image = Postingan::select('content')->where('post_id', (int)$id)->first();
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
}
