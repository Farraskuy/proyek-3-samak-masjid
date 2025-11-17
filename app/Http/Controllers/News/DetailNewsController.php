<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailNewsController extends Controller
{
 function return_resource($slug_from_view){

    $data_posts= \DB::table('posts')->select('content')->where('slug',$slug_from_view)->first();

    $kontent_html_tag = $data_posts->content;

    // BUNGKUS AGAR TIDAK DI-MERGE
    $kontent_html_tag = "<div>$kontent_html_tag</div>";

    $obj_html = new \DOMDocument();
    libxml_use_internal_errors(true);

    // load HTML wrapper
    $obj_html->loadHTML($kontent_html_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    // Tambah prefix /storage/
    $img = $obj_html->getElementsByTagName("img");
    foreach($img as $image_tag){
        $src = $image_tag->getAttribute('src');  
        if (!str_starts_with($src, '/storage/')) {
            $image_tag->setAttribute('src', '/storage/' . $src);
        }
    }

    // Ambil isi dalam wrapper div
    $updated_html = $obj_html->saveHTML($obj_html->documentElement);

    // Hapus <div> pembungkus
    $updated_html = preg_replace('/^<div>|<\/div>$/', '', $updated_html);

    return view('post.fitur_detail_postingan',['data_posts' => $updated_html]);
}

}

