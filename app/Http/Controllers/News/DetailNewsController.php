<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailNewsController extends Controller
{
    function return_resource(){
        $data_posts= \DB::table('posts')->select('content')->where('post_id',20)->first();
        return view('test',['data_posts'=> $data_posts]);
    }
}
