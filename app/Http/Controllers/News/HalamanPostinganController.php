<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HalamanPostinganController extends Controller
{
        function return_resource(){
        $data_posts= \DB::table('posts')->select('*')->get();
        return view('post.halaman_postingan',['data_posts'=> $data_posts]);
    }
}
