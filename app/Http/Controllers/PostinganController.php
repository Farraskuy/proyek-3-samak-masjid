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
        $filter = $request->query('filter');
        $keyword = $request->query('keyword');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Postingan::query();

        // Tampilkan hanya yang published
        $query->where('status', 'published');

        if (!empty($filter)) {
            $query->whereRaw('LOWER(kategori) = ?', [strtolower($filter)]);
        }

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $cacheKey = 'postingan.index:' . md5($request->fullUrl());
        $data_posts = Cache::remember($cacheKey, now()->addMinutes(0), function () use ($query) {
            return $query->orderBy('created_at', 'desc')->paginate(9);
        });

        if (method_exists($data_posts, 'withQueryString')) {
            $data_posts = $data_posts->withQueryString();
        }

        return view('client.postingan.index', ['data_posts' => $data_posts]);
    }


    // Show detail page by slug (previously DetailPostinganController::return_resource)
    public function showDetail($slug_from_view)
    {

        $post = Postingan::where('slug', $slug_from_view)->firstOrFail();


        if ($post->status !== 'published') {
                abort(404); 
        }

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
        $cachedHtml = Cache::remember($cacheKey, now()->addMinutes(0), function () use ($updated_html) {
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

         if ( !optional($request->user())->hasPermission('create_posts')  ) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title_view' => 'required|string|max:255',
            'keterangan_view' => 'nullable|string',
            'kategori_view' => 'required|string',
            'image_view' => 'nullable|image|max:10000',
            'content_view' => 'required|string'
        ]);

        $featuredImagePath = null;
        if ($request->hasFile('image_view')) {
            $image = $request->file('image_view');
            $newName = uniqid() . '_' . $image->getClientOriginalName();
            $featuredImagePath = $image->storeAs('news/images', $newName,'public');
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
            'status' => $request->status_view,
            'user_id' => auth()->id()
        ]);

        return redirect()->to('/admin/postingan')->with('success', 'Data berhasil disimpan!');
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

                Storage::disk('public')->put($path, $data);

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


        if ( !optional($request->user())->hasPermission('view_posts')  ) {
            abort(403, 'Unauthorized');
        }

        $perPage = $request->query('showing', 50);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 'all');

        $query = Postingan::orderBy('created_at', 'desc');

        // Add search filter
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        // Add status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($perPage === 'all') {
            $post = $query->get();
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 50;
            $post = $query->paginate($perPage)->withQueryString();
        }

        return view('admin.postingan.index')->with(['data' => $post, 'status' => $status]);
    }

    // Approval index for super-admin: list postingans awaiting approval
public function approvalIndex(Request $request)
    {
        // 1. Cek Permission
        if (!optional($request->user())->hasPermission('approve_posts')) {
            abort(403, 'Unauthorized');
        }

        // 2. Ambil Parameter Request
        $perPage = $request->query('showing', 50);
        $status  = $request->query('status', 'pending'); // Default status 'pending'
        $keyword = $request->query('keyword', '');       // Ambil keyword pencarian

        // 3. Mulai Query
        $query = Postingan::query();

        // A. Filter berdasarkan Status (Wajib untuk halaman approval)
        $query->where('status', $status);

        // B. Filter Pencarian (Keyword)
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('keterangan', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%")
                  // Opsional: Cari berdasarkan nama penulis jika relasi ada
                  ->orWhereHas('creator', function($userQuery) use ($keyword) {
                      $userQuery->where('full_name', 'like', "%{$keyword}%");
                  });
            });
        }

        // C. Order By
        $query->orderBy('created_at', 'desc');

        // 4. Eksekusi Data (Pagination / Get All)
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 50;
            // withQueryString() penting agar saat pindah halaman (page 2), status & keyword terbawa
            $data = $query->paginate($perPage)->withQueryString(); 
        }

        return view('admin.postingan.approval_index')->with([
            'data'   => $data, 
            'status' => $status
        ]);
    }

    // Show approval detail + preview

        public function approvalShow(Request $request, $id)
        {
            if (!optional($request->user())->hasPermission('approve_posts')) {
                abort(403, 'Unauthorized');
            }

            $post = Postingan::where('id', $id)->firstOrFail();

            // Tambahkan /storage/ hanya untuk tag <img>
            $post->content = preg_replace(
                '/<img\s+[^>]*src="(news\/[^"]+)"/i',
                '<img src="/storage/$1"',
                $post->content
            );

            return view('admin.postingan.approval_detail', compact('post'));
        }
public function approvalUpdate(Request $request, $id)
{
        // 1. Cek Permission
        if (!optional($request->user())->hasPermission('approve_posts')) {
            abort(403, 'Unauthorized');
        }

        // 2. Cari Postingan
        $post = Postingan::findOrFail($id);
        $currentStatus = strtolower($post->status);

        // 3. Validasi Input Form
        $validated = $request->validate([
            'decision' => 'required|in:published,revisi,arsip,draft',
            'note'     => 'required_if:decision,revisi|nullable|string', 
        ], [
            'note.required_if' => 'Catatan revisi wajib diisi jika status diubah menjadi Revisi.',
            'decision.required' => 'Anda harus memilih salah satu keputusan.',
        ]);

        $decision = strtolower($validated['decision']);

        // 4. Validasi Business Rule (STRICT MODE)
        // Mencegah perubahan status yang tidak sesuai alur
        $isAllowed = false;

        switch ($currentStatus) {
            case 'pending':
                // Pending -> Revisi atau Published
                if (in_array($decision, ['revisi', 'published'])) {
                    $isAllowed = true;
                }
                break;

            case 'published':
                // Published -> Arsip saja
                if ($decision === 'arsip') {
                    $isAllowed = true;
                }
                break;

            case 'arsip':
                // PERUBAHAN DISINI: Arsip -> Published (sebelumnya Draft)
                // Jika dari arsip ingin ditayangkan kembali
                if ($decision === 'published') {
                    $isAllowed = true;
                }
                break;
            
            // Status Draft dan Revisi TIDAK BOLEH diproses oleh Admin (Action terkunci)
            case 'draft':
            case 'revisi':
                $isAllowed = false;
                break;

            default:
                $isAllowed = false;
                break;
        }

        // Jika alur tidak valid atau statusnya terlarang
        if (!$isAllowed) {
            return back()->withErrors([
                'decision' => "Tindakan DITOLAK. Perubahan status dari '$currentStatus' ke '$decision' melanggar aturan sistem."
            ])->withInput();
        }

        // 5. Eksekusi Perubahan Status
        $post->status = $decision;

        if ($decision === 'published') {
            // Logika jika di-publish (baik dari Pending maupun dari Arsip)
            // Kita set ulang published_at ke waktu sekarang (re-publish)
            $post->published_at  = now();
            $post->approved_by   = auth()->id();
            $post->approved_at   = now(); 
            $post->approval_note = null; // Hapus catatan revisi jika ada

        } elseif ($decision === 'revisi') {
            $post->approval_note = $validated['note'];
            $post->published_at  = null; 
            $post->approved_by   = null; 
            $post->approved_at   = null;

        } else {
            // Logika untuk Arsip (decision == 'arsip')
            // Tanggal published di-null-kan karena masuk arsip
            $post->published_at = null;
        }

        $post->save();

        return redirect()->route('admin.postingan.index')
            ->with('success', 'Status postingan berhasil diperbarui menjadi ' . ucfirst($decision));
}

    // Delete article and associated images (previously ShowPostingan::deleteArtikel)
    public function deleteArtikel(Request $request, $id)
    {

        if ( !optional($request->user())->hasPermission('delete_posts')  ) {
            abort(403, 'Unauthorized');
        }


        $this->search_delete_featured_image($id);
        $this->search_delete_kontent_image($id);
        Postingan::where('id', (int) $id)->delete();
        
        return redirect()->back()->with('success', 'Artikel berhasil dihapus');
    }

    // Delete featured image file
    protected function search_delete_featured_image($id)
    {
        $featured_image_fc = Postingan::select('featured_image_url')->where('id', (int) $id)->first();
        if ($featured_image_fc && $featured_image_fc->featured_image_url) {
            $path = $featured_image_fc->featured_image_url;
            Storage::disk('public')->delete($path);
        }
    }

    // Delete images embedded in content
    protected function search_delete_kontent_image($id)
    {
        $kontent_image = Postingan::select('content')->where('id', (int) $id)->first();
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


public function edit(Request $request, $id)
{
    if (!optional($request->user())->hasPermission('edit_posts')) {
        abort(403, 'Unauthorized');
    }

    $post = Postingan::where('id', $id)->firstOrFail();

    // --- ATURAN BARU ---
    // Cegah user masuk ke edit apabila postingan tidak berstatus revisi
    if (strtolower($post->status) !== 'revisi' && strtolower($post->status) !== 'draft'    &&    strtolower($post->status) !== 'arsip') {
        abort(403, 'Postingan hanya dapat diedit jika statusnya "Revisi" atau "Draft" atau "Arsip".');
    }
    // -------------------

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
            'keterangan' => 'nullable|sometimes|string',
            'keterangan_view' => 'nullable|sometimes|string',
            'kategori' => 'sometimes|required|string',
            'kategori_view' => 'sometimes|required|string',
            'featured_image_url' => 'sometimes|nullable',
            'image_view' => 'sometimes|nullable|image|max:2048', // Boleh null jika tidak ganti gambar
            'content' => 'required|sometimes|string',
            'content_view' => 'required|sometimes|string'
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
            $featuredImagePath = $image->storeAs('news/images', $newName,'public');
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

            $this->deleteRemovedQuillImages($post->content, $content);
        }


        // 5. Handle Slug (buat baru jika judul berubah)
        $slug = $post->slug;
        $newTitle = $request->input('title_view') ?? $request->input('title');
        if ($newTitle && $post->title !== $newTitle) {
            $slug = Str::slug($newTitle) . '-' . 'DAKWAH' . uniqid();
        }

        // 6. Update ke database
        // Update: set approval_status to  and mark not published to require re-approval
        $post->update([
            'title' => $newTitle ?? ($validated['title_view'] ?? $post->title),
            'slug' => $slug,
            'keterangan' => $request->input('keterangan_view') ?? $request->input('keterangan') ?? $post->keterangan,
            'featured_image_url' => $featuredImagePath,
            'content' => $content ?? $post->content,
            'kategori' => $request->input('kategori_view') ?? $request->input('kategori') ?? $post->kategori,
            'status' => $request->status_view,
            'approved_by' => null,
            'approved_at' => null,
            'updated_at' => now()
        ]);

        return redirect()->to('/admin/postingan')->with('success', 'Data berhasil diupdate!');
    }


private function deleteRemovedQuillImages($oldContent, $newContent)
    {
        // 1. Ambil path dari konten lama
        preg_match_all('/<img[^>]+src="(news\/[^"]+)"/i', $oldContent, $oldMatches);
        $oldImages = $oldMatches[1] ?? [];

        // 2. Ambil path dari konten baru
        preg_match_all('/<img[^>]+src="(news\/[^"]+)"/i', $newContent, $newMatches);
        $newImages = $newMatches[1] ?? [];

        // 3. Cari gambar yang ada di lama TAPI tidak ada di baru (artinya dihapus)
        $deletedImages = array_diff($oldImages, $newImages);

        // 4. Hapus file fisik di storage PUBLIC
        foreach ($deletedImages as $img) {
            // PERBAIKAN: Tambahkan disk('public')
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }
    }


}




