<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    protected function perPage(Request $request)
    {
        $showing = $request->query('showing', 50);
        if ($showing === 'all') return 1000;
        $n = (int) $showing;
        return $n > 0 ? $n : 50;
    }

    public function index(Request $request)
    {
        $perPage = $this->perPage($request);
        $data = GalleryAlbum::orderBy('created_at', 'desc')
                             ->paginate($perPage)->withQueryString();

        return view('admin.galeri.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    // STORE (Tambah Album)
    public function store(Request $request)
    {
        $request->validate([
            'album_name'   => 'required|string|max:100',
            'cover_photo'  => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photos.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // Simpan album
        $album = GalleryAlbum::create([
            'album_name' => $request->album_name,
            'description' => '-',
            'created_by' => Auth::id(),
        ]);

        // Simpan Cover
        $coverPath = $request->file('cover_photo')->store('gallery/covers', 'public');

        GalleryPhoto::create([
            'album_id' => $album->album_id,
            'image_url' => $coverPath,
            'caption' => 'Cover Album',
            'uploaded_by' => Auth::id(),
        ]);

        // Simpan Foto dengan Caption
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $img) {

                $path = $img->store('gallery/photos', 'public');

                GalleryPhoto::create([
                    'album_id' => $album->album_id,
                    'image_url' => $path,
                    'caption' => $request->captions[$index] ?? '',  // CAPTION BARU
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('admin.galeri')
                         ->with('success', 'Album berhasil ditambahkan!');
    }

    // EDIT FORM
    public function edit($album_id)
    {
        $album = GalleryAlbum::with(['photos', 'cover'])
                             ->findOrFail($album_id);

        return view('admin.galeri.edit', compact('album'));
    }

    // UPDATE (Edit Album)
    public function update(Request $request, $album_id)
    {
        $album = GalleryAlbum::findOrFail($album_id);

        $request->validate([
            'album_name' => 'required|string|max:100',
            'cover_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        /* Update Nama */
        $album->album_name = $request->album_name;
        $album->save();

        // UPDATE CAPTION FOTO LAMA
        if ($request->old_captions) {
            foreach ($request->old_captions as $photo_id => $caption) {

                $photo = GalleryPhoto::find($photo_id);

                if ($photo && $photo->caption !== 'Cover Album') {
                    $photo->caption = $caption !== null ? $caption : '';
                    $photo->save();
                }
            }
        }

        // HAPUS COVER JIKA DIMINTA
        if ($request->delete_cover == 1) {
            $cover = GalleryPhoto::where('album_id', $album_id)
                                 ->where('caption', 'Cover Album')
                                 ->first();

            if ($cover) {
                Storage::disk('public')->delete($cover->image_url);
                $cover->delete();
            }
        }

        // UPLOAD COVER BARU
        if ($request->hasFile('cover_photo')) {

            $old = GalleryPhoto::where('album_id', $album_id)
                               ->where('caption', 'Cover Album')->first();

            if ($old) {
                Storage::disk('public')->delete($old->image_url);
                $old->delete();
            }

            $path = $request->file('cover_photo')->store('gallery/covers', 'public');

            GalleryPhoto::create([
                'album_id' => $album_id,
                'image_url' => $path,
                'caption' => 'Cover Album',
                'uploaded_by' => Auth::id(),
            ]);
        }

        // TAMBAH FOTO BARU + CAPTION BARU
        if ($request->hasFile('photos')) {

            foreach ($request->file('photos') as $index => $img) {

                $path = $img->store('gallery/photos', 'public');

                GalleryPhoto::create([
                    'album_id' => $album_id,
                    'image_url' => $path,
                    'caption' => $request->new_captions[$index] ?? '', // CAPTION BARU
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        // HAPUS FOTO LAMA
        if ($request->filled('delete_photos')) {
            
            $deletePhotosRaw = $request->delete_photos;
            
            if (is_string($deletePhotosRaw)) {
                $deleteIds = json_decode($deletePhotosRaw, true);
            } else if (is_array($deletePhotosRaw)) {
                $deleteIds = $deletePhotosRaw;
            } else {
                $deleteIds = [];
            }

            // Loop dan hapus foto
            if (is_array($deleteIds) && count($deleteIds) > 0) {
                foreach ($deleteIds as $photoId) {
                    $photoId = (int) $photoId;
                    
                    if ($photoId > 0) {
                        $photo = GalleryPhoto::find($photoId);
                        
                        if ($photo && $photo->caption !== 'Cover Album') {
                            Storage::disk('public')->delete($photo->image_url);
                            $photo->delete();
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.galeri')
                         ->with('success', 'Album berhasil diperbarui!');
    }

    // DELETE (GET Request)
    public function delete($album_id)
    {
        $album = GalleryAlbum::with('photos')->findOrFail($album_id);

        foreach ($album->photos as $photo) {
            Storage::disk('public')->delete($photo->image_url);
            $photo->delete();
        }

        $album->delete();

        return redirect()->route('admin.galeri')
                         ->with('success', 'Album berhasil dihapus!');
    }

    // CLIENT
    public function guestIndex()
    {
        $albums = GalleryAlbum::with(['photos', 'cover'])
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('client.galeri.index', compact('albums'));
    }

    public function guestShow($album_id)
    {
        $album = GalleryAlbum::with('photos')->findOrFail($album_id);

        return view('client.galeri.detail', compact('album'));
    }
}
