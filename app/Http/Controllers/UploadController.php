<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // maks 2MB
        ]);

        $path = $request->file('image')->store('public/uploads');
        $url = Storage::url($path);

        return response()->json(['url' => $url]);
    }
}


?>