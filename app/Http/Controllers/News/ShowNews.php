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
        $this->search_delete_kontent_image($id);
        \DB::table('posts')->where('post_id', (int)$id)->delete();
        return redirect()->back()->with('status', 'Artikel berhasil dihapus');
    }


    public function search_delete_featured_image($id){
        $featured_image_fc= \DB::table('posts')->select('featured_image_url')->where('post_id',(int)$id)->first();
        $path = $featured_image_fc->featured_image_url;
        \Storage::disk('public')->delete($path);
    }


    Public function search_delete_kontent_image($id){
        $kontent_image= \DB::table('posts')->select('content')->where('post_id',(int)$id)->first();
        $kontent_html_tag = $kontent_image->content;

        $obj_html = new \DOMDocument();
        libxml_use_internal_errors(true);
        $obj_html->loadHTML($kontent_html_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $img = $obj_html->getElementsByTagName("img");
        
        foreach($img as $image_tag){
            $src = $image_tag->getAttribute('src');  
            \Storage::disk('public')->delete($src);  
        }
    }
}


