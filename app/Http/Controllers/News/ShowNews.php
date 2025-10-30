<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowNews extends Controller
{

    
    public function getEditArtikel(){
        $post = \DB::table('posts')->select('title','status','kategori','slug','post_id')->get();
        return view("edit_artikel")->with('post_data',$post);
    }

}
