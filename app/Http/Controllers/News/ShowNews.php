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


    public function deleteArtikel($id)
    {
        \DB::table('posts')->where('post_id', $id)->delete();
        return redirect()->back()->with('status', 'Artikel berhasil dihapus');
    }
}
