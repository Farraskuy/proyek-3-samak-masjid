<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaticPageController extends Controller
{
    /**
     * Display the "Tentang Kami" page for clients
     */
    public function showAboutUs()
    {
        $page = StaticPage::firstOrFail();

        // Process images in content
        $content_html = $this->processContentImages($page->content);

        return view('client.static-pages.about-us', [
            'page' => $page,
            'content_html' => $content_html
        ]);
    }

    /**
     * Admin: Display index page for static pages management
     */
    public function indexAdmin(Request $request)
    {


        $perPage = $request->query('showing', 50);
        $keyword = $request->query('keyword', '');

        $query = StaticPage::query();

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $query->orderBy('updated_at', 'desc');

        if ($perPage === 'all') {
            $pages = $query->get();
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 50;
            $pages = $query->paginate($perPage)->withQueryString();
        }

        return view('admin.static-pages.index', compact('pages'));
    }

    /**
     * Admin: Show edit form for static page
     */
    public function edit($id)
    {
        $page = StaticPage::findOrFail($id);

        $page->content = preg_replace(
        '/<img\s+[^>]*src="(static-pages\/[^"]+)"/i',
        '<img src="/storage/$1"',
        $page->content
        );

        return view('admin.static-pages.edit', compact('page'));
    }

    /**
     * Admin: Update static page
     */
   public function update(Request $request, $id)
    {
        $page = StaticPage::findOrFail($id);

        // 1. Validasi Input
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:static_pages,title,' . $id,
            'description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // 2. Proses Featured Image (Gambar Utama)
        if ($request->hasFile('featured_image_url')) {
            // Hapus gambar lama jika ada
            if ($page->featured_image_url && Storage::exists($page->featured_image_url)) {
                Storage::delete($page->featured_image_url);
            }

            // Simpan gambar baru
            $image = $request->file('featured_image_url');
            $newName = uniqid() . '_' . $image->getClientOriginalName();
            $validated['featured_image_url'] = $image->storeAs('static-pages/images', $newName);
        }

        // 3. Proses Konten (Isi Halaman / Quill)
        $content = $request->input('content');

        if ($content) {
            // A. Bersihkan Path (Hapus /storage/ agar relative path)
            $content = preg_replace(
                '/src="\/storage\/(static-pages\/[^"]+)"/i',
                'src="$1"',
                $content
            );

            // B. Proses Gambar Base64 Baru
            $content = $this->processQuillImages($content);

            // C. Delete Unused Images
            $this->deleteRemovedQuillImages($page->content, $content);

            $validated['content'] = $content;
        }

        $validated['updated_by_admin'] = Auth::id();

        // --- BAGIAN KHUSUS TIMESTAMPS ---
        // 1. Set created_at jadi waktu sekarang

        // 2. Set updated_at jadi sekarang 
        $validated['updated_at'] = now('Asia/Jakarta');

        // 3. PENTING: Matikan fitur auto-timestamp bawaan Laravel untuk model ini sementara.
        // Kalau ini tidak dimatikan, Laravel akan memaksa mengisi updated_at lagi saat perintah update() dijalankan.
        $page->timestamps = false; 

        // 4. Update Database
        DB::beginTransaction();
        try {
            $page->update($validated);
            
            DB::commit();

            return redirect()->route('admin.static-pages.index')
                ->with('success', 'Halaman statis berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui halaman: ' . $e->getMessage());
        }
    }




            public function create()
        {
            // Cek total data di database
            $count = StaticPage::count();

            // Jika sudah ada 1 atau lebih balikin ke sebelumnya yahahaha
            if ($count >= 1) {
                return redirect()->route('admin.static-pages.index')
                    ->with('error', 'Maksimal hanya boleh ada 1 Halaman Statis. Silakan edit halaman yang sudah ada.');
            }

            // Jika belum ada data, tampilkan view create tanpa mengirim variabel $page
            return view('admin.static-pages.tambah_static-pages');
        }

        public function store(Request $request)
        {
            // 1. Double Check Limit (Keamanan Ekstra)
            // Mencegah user yang mencoba bypass URL atau inspect element
            if (StaticPage::count() >= 1) {
                return redirect()->route('admin.static-pages.index')
                    ->with('error', 'Kuota halaman statis sudah penuh.');
            }

            // 2. Validasi Input
            $validated = $request->validate([
                'title'              => 'required|string|max:255|unique:static_pages,title',
                'description'        => 'nullable|string|max:500',
                'content'            => 'required|string',
                // Pada fitur Add, biasanya gambar wajib (required), tapi jika opsional ganti jadi nullable
                'featured_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120' 
            ]);


        // ==========================================================
        // ==========================================================
        $validated['slug'] = Str::slug($request->title);
        // ==========================================================


            // 3. Proses Upload Featured Image
            if ($request->hasFile('featured_image_url')) {
                $image = $request->file('featured_image_url');
                $newName = uniqid() . '_' . $image->getClientOriginalName();
                // Simpan path gambar ke array validated
                $validated['featured_image_url'] = $image->storeAs('static-pages/images', $newName);
            }

            // 4. Proses Konten (Quill Editor)
            $content = $request->input('content');
            
            if ($content) {
                // Proses gambar Base64 yang di-paste di editor menjadi file fisik
                // (Asumsi function processQuillImages sudah ada di class ini atau trait, seperti di method update)
                $content = $this->processQuillImages($content);
                
                $validated['content'] = $content;
            }

            $validated['created_by'] = Auth::id(); // Sesuaikan dengan kolom di DB kamu (created_by / updated_by)

            // 5. Simpan ke Database
            DB::beginTransaction();
            try {
                StaticPage::create($validated);
                
                DB::commit();

                return redirect()->route('admin.static-pages.index')
                    ->with('success', 'Halaman statis berhasil dibuat!');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                
                // Hapus gambar featured jika DB gagal simpan agar tidak jadi sampah
                if (isset($validated['featured_image_url'])) {
                    Storage::delete($validated['featured_image_url']);
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }








    /**
     * Process base64 images in Quill content and store them
     */
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
                $path = 'static-pages/content/' . $fileName;

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





    /**
     * Menghapus gambar Quill yang sudah tidak ada di konten baru (Cleanup)
     */
    private function deleteRemovedQuillImages($oldContent, $newContent)
    {
        // 1. Ambil semua path IMG dari konten LAMA (Database)
        // Regex disesuaikan dengan folder: static-pages/
        preg_match_all('/<img[^>]+src="(static-pages\/[^"]+)"/i', $oldContent, $oldMatches);
        $oldImages = $oldMatches[1] ?? [];

        // 2. Ambil semua path IMG dari konten BARU (Input User yang sudah diproses)
        preg_match_all('/<img[^>]+src="(static-pages\/[^"]+)"/i', $newContent, $newMatches);
        $newImages = $newMatches[1] ?? [];

        // 3. Cari gambar yang ada di LAMA tapi TIDAK ADA di BARU
        $deletedImages = array_diff($oldImages, $newImages);

        // 4. Hapus file fisik di storage
        foreach ($deletedImages as $img) {
            if (Storage::exists($img)) {
                Storage::delete($img);
            }
        }
    }


    

    /**
     * Process content images for display (add storage prefix if needed)
     */
    private function processContentImages($content)
    {
        $kontent_html_tag = $content;

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

        return $updated_html;
    }
}

