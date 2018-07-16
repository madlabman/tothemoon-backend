<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        try {
            $news = NewsPost::latest()->get();
            return response()->json([
                'status'    => 'success',
                'news'      => $news,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status'    => 'error',
            ], 500);
        }
    }
}