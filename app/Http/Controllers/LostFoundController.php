<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LostAndFoundItem;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LostFoundController extends Controller
{
    protected function perPage(Request $request)
    {
        $showing = $request->query('showing', 50);
        if ($showing === 'all') {
            return 1000;
        }
        $n = (int) $showing;
        return $n > 0 ? $n : 50;
    }

    public function adminIndex(Request $request)
    {
        $perPage = $this->perPage($request);
        $query = LostAndFoundItem::with('user');
        $data = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        return view('admin.lost-found.index', ['data' => $data]);
    }
}
