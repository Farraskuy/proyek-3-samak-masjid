<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Postingan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PostinganController extends Controller
{
    // Show listing page (previously HalamanPostinganController::return_resource)
    public function index(Request $request)
    {
        $filter = $request->query('filter'); // ?filter=...
        $keyword = $request->query('keyword');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Postingan::query();

        if (!empty($filter)) {
            $query->whereRaw('LOWER(kategori) = ?', [strtolower($filter)]);
        }

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $cacheKey = 'postingan.index:' . md5($request->fullUrl());
        $data_posts = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($query) {
            return $query->orderBy('created_at', 'desc')->paginate(9);
        });

        // ensure paginator keeps query string
        if (method_exists($data_posts, 'withQueryString')) {
            $data_posts = $data_posts->withQueryString();
        }

        return view('client.postingan.index', ['data_posts' => $data_posts]);
    }

    // Show detail page by slug (previously DetailPostinganController::return_resource)
    public function showDetail($slug_from_view)
    {

        $post = Postingan::where('slug', $slug_from_view)->firstOrFail();

        $kontent_html_tag = $post->content;

        // Wrap to avoid merging
        $kontent_html_tag = "<div>$kontent_html_tag</div>";

        $obj_html = new \DOMDocument();
        libxml_use_internal_errors(true);

        $obj_html->loadHTML($kontent_html_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Prefix storage/ for images
        $img = $obj_html->getElementsByTagName("img");
        /** @var \DOMElement $image_tag */
        foreach ($img as $image_tag) {
            /** @var string $src */
            $src = $image_tag->getAttribute('src');
            if (!str_starts_with($src, '/storage/') && !str_starts_with($src, 'http')) {
                $image_tag->setAttribute('src', '/storage/' . ltrim($src, '/'));
            }
        }

        $updated_html = $obj_html->saveHTML($obj_html->documentElement);
        $updated_html = preg_replace(['/^<div>/', '/<\/div>$/'], ['', ''], $updated_html);

        // cache detail by slug to speed up repeated views
        $cacheKey = 'postingan.show:' . $post->slug;
        $cachedHtml = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($updated_html) {
            return $updated_html;
        });

        return view('client.postingan.detail', ['post' => $post, 'content_html' => $cachedHtml]);
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
            'image_view' => 'nullable|image|max:10000',
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

        Postingan::create([
            'title' => $validated['title_view'],
            'slug' => $slug . '-' . 'DAKWAH' . uniqid(),
            'keterangan' => $validated['keterangan_view'],
            'featured_image_url' => $featuredImagePath,
            'content' => $content,
            'kategori' => $validated['kategori_view'],
            'created_at' => now(),
            'approval_status' => 'pending',
            'status' => $request->status_view,
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

        /** @var \DOMElement $img */
        foreach ($images as $img) {
            /** @var string $src */
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
    public function indexAdmin(Request $request)
    {
        $perPage = $request->query('showing', 50);
        $keyword = $request->query('keyword', '');

        $query = Postingan::orderBy('created_at', 'desc');

        // Add search filter
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('keterangan', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        if ($perPage === 'all') {
            $post = $query->get();
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 50;
            $post = $query->paginate($perPage)->withQueryString();
        }

        return view('admin.postingan.index')->with('data', $post);
    }

    // Approval index for super-admin: list postingans awaiting approval
    public function approvalIndex(Request $request)
    {
        $perPage = $request->query('showing', 50);
        $query = Postingan::where('approval_status', 'pending')->orderBy('created_at', 'desc');

        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 50;
            $data = $query->paginate($perPage)->withQueryString();
        }

        return view('admin.postingan.approval_index')->with('data', $data);
    }

    // Show approval detail + preview
    public function approvalShow($id)
    {
        $post = Postingan::where('id', (int)$id)->firstOrFail();
        return view('admin.postingan.approval_detail')->with('post', $post);
    }

    // Handle approval action (approve/reject/revision)
    public function approvalUpdate(Request $request, $id)
    {
        $post = Postingan::where('id', (int)$id)->firstOrFail();

        $validated = $request->validate([
            'decision' => 'required|in:approve,reject,revision',
            'status' => 'nullable|in:published,not published,revisi',
            'note' => 'nullable|string'
        ]);

        $decision = $validated['decision'];

        if ($decision === 'approve') {
            $post->approval_status = 'approved';
            $post->approval_note = $validated['note'] ?? null;
            $post->approved_by = optional($request->user())->id ?? null;
            $post->approved_at = now();
            // optionally change publication status if provided
            if (!empty($validated['status']) && $validated['status'] === 'published') {
                $post->status = 'published';
                $post->published_at = now();
            }
        } elseif ($decision === 'reject') {
            $post->approval_status = 'rejected';
            $post->approval_note = $validated['note'] ?? null;
            $post->approved_by = optional($request->user())->id ?? null;
            $post->approved_at = now();
        } else { // revision
            $post->approval_status = 'revision';
            $post->approval_note = $validated['note'] ?? null;
            // mark post status as 'revisi' so admin sees it needs edits
            $post->status = 'revisi';
        }

        $post->save();

        return redirect()->route('postingan.admin.approval.index')->with('status', 'Keputusan approval disimpan.');
    }

    // Delete article and associated images (previously ShowPostingan::deleteArtikel)
    public function deleteArtikel(Request $request, $id)
    {
        // Only allow super admin to delete
        if (optional($request->user())->role !== 'super admin') {
            abort(403, 'Unauthorized');
        }

        $this->search_delete_featured_image($id);
        $this->search_delete_kontent_image($id);
        Postingan::where('id', (int)$id)->delete();
        return redirect()->back()->with('status', 'Artikel berhasil dihapus');
    }

    // Delete featured image file
    protected function search_delete_featured_image($id)
    {
        $featured_image_fc = Postingan::select('featured_image_url')->where('id', (int)$id)->first();
        if ($featured_image_fc && $featured_image_fc->featured_image_url) {
            $path = $featured_image_fc->featured_image_url;
            Storage::disk('public')->delete($path);
        }
    }

    // Delete images embedded in content
    protected function search_delete_kontent_image($id)
    {
        $kontent_image = Postingan::select('content')->where('id', (int)$id)->first();
        if (!$kontent_image) {
            return;
        }
        $kontent_html_tag = $kontent_image->content;

        $obj_html = new \DOMDocument();
        libxml_use_internal_errors(true);
        $obj_html->loadHTML($kontent_html_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $img = $obj_html->getElementsByTagName("img");

        /** @var \DOMElement $image_tag */
        foreach ($img as $image_tag) {
            /** @var string $src */
            $src = $image_tag->getAttribute('src');
            Storage::disk('public')->delete($src);
        }
    }

    public function edit($id)
    {
        $post = Postingan::where('id', $id)->firstOrFail();

        // Tambahkan /storage/ hanya untuk tag <img>
        $post->content = preg_replace(
            '/<img\s+[^>]*src="(news\/[^"]+)"/i',
            '<img src="/storage/$1"',
            $post->content
        );

        return view('admin.postingan.edit', compact('post'));
    }





    public function update(Request $request, $id)
    {
        // 1. Ambil data post yang ada
        $post = Postingan::where('id', $id)->firstOrFail();

        // 2. Validasi input
        // accept both legacy field names and new names from blade
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'title_view' => 'sometimes|required|string|max:255',
            'keterangan' => 'sometimes|required|string',
            'keterangan_view' => 'sometimes|required|string',
            'kategori' => 'sometimes|required|string',
            'kategori_view' => 'sometimes|required|string',
            'featured_image_url' => 'sometimes|nullable',
            'image_view' => 'sometimes|nullable|image|max:2048', // Boleh null jika tidak ganti gambar
            'content' => 'sometimes|nullable|string',
            'content_view' => 'sometimes|nullable|string'
        ]);

        // 3. Handle Gambar Header (Featured Image)
        $featuredImagePath = $post->featured_image_url; // Default pakai gambar lama

        if ($request->hasFile('image_view') || $request->hasFile('featured_image_url')) {
            // Jika ada gambar baru di-upload:

            // Hapus gambar lama (jika ada)
            if ($post->featured_image_url) {
                Storage::delete($post->featured_image_url);
            }

            // Simpan gambar baru
            $image = $request->file('image_view') ?? $request->file('featured_image_url');
            $newName = uniqid() . '_' . $image->getClientOriginalName();
            $featuredImagePath = $image->storeAs('public/news/images', $newName);
        }

        // 4. Handle Konten Quill (termasuk gambar base64 baru)
        $content = $request->input('content_view') ?? $request->input('content');

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
        $newTitle = $request->input('title_view') ?? $request->input('title');
        if ($newTitle && $post->title !== $newTitle) {
            $slug = Str::slug($newTitle) . '-' . 'DAKWAH' . uniqid();
        }

        // 6. Update ke database
        // Update: set approval_status to pending and mark not published to require re-approval
        $post->update([
            'title' => $newTitle ?? ($validated['title_view'] ?? $post->title),
            'slug' => $slug,
            'keterangan' => $request->input('keterangan_view') ?? $request->input('keterangan') ?? $post->keterangan,
            'featured_image_url' => $featuredImagePath,
            'content' => $content ?? $post->content,
            'kategori' => $request->input('kategori_view') ?? $request->input('kategori') ?? $post->kategori,
            'approval_status' => 'pending',
            'status' => 'not published',
            'approved_by' => null,
            'approved_at' => null,
            'updated_at' => now()
        ]);

        return redirect()->to('/admin/postingan')->with('success_post_disimpan_di_database', 'Data berhasil diupdate!');
    }
}
