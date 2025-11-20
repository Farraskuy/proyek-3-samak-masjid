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
        $page = StaticPage::where('slug', 'tentang-kami')->firstOrFail();

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
        return view('admin.static-pages.edit', compact('page'));
    }

    /**
     * Admin: Update static page
     */
    public function update(Request $request, $id)
    {
        $page = StaticPage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:static_pages,title,' . $id,
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Process featured image if uploaded
        if ($request->hasFile('featured_image_url')) {
            // Delete old image if exists
            if ($page->featured_image_url && Storage::exists($page->featured_image_url)) {
                Storage::delete($page->featured_image_url);
            }

            $image = $request->file('featured_image_url');
            $newName = uniqid() . '_' . $image->getClientOriginalName();
            $validated['featured_image_url'] = $image->storeAs('static-pages/images', $newName);
        }

        // Process content images (from Quill editor)
        if ($validated['content']) {
            $validated['content'] = $this->processQuillImages($validated['content']);
        }

        $validated['updated_by_admin'] = Auth::id();

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

