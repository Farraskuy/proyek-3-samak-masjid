<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class ShowNews extends Controller
{

    
    public function getEditArtikel(){
        $post = \DB::table('posts')->select('title','status','kategori','slug','post_id')->get();
        return view("edit_artikel")->with('post_data',$post);
    }


    public function deleteArtikel($id)
    {
        $this->search_delete_featured_image($id);
        \DB::table('posts')->where('post_id', (int)$id)->delete();
        return redirect()->back()->with('status', 'Artikel berhasil dihapus');
    }


    public function search_delete_featured_image($id){
        $featured_image_fc= \DB::table('posts')->select('featured_image_url')->where('post_id',(int)$id)->first();
        $path = 'public/'. $featured_image_fc->featured_image_url;
        \Storage::delete($path);
    }

}


