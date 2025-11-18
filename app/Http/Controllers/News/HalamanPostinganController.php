<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class HalamanPostinganController extends Controller
{
    function return_resource(Request $request){
        
        $filter = $request->query('filter'); // ?filter=...

        $query = \DB::table('posts');

            if (!empty($filter)) {
        $query->where('kategori', $filter);
    }
        $query->orderBy('created_at', 'desc');
        
        $data_posts = $query->paginate(9)->appends($request->query());

        return view('post.halaman_postingan',['data_posts'=> $data_posts]);
    }
}

