<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailNewsController extends Controller
{
    function return_resource($slug_from_view){
        $data_posts= \DB::table('posts')->select('content')->where('slug',$slug_from_view)->first();
        return view('post.fitur_detail_postingan',['data_posts'=> $data_posts]);
    }
}
