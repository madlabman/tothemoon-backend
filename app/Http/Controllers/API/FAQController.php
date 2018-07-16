<?php

namespace App\Http\Controllers\API;

use App\FAQPost;
use App\Http\Controllers\Controller;

class FAQController extends Controller
{
    public function index()
    {
        try {
            $posts = FAQPost::latest()->get();
            return response()->json([
                'status'    => 'success',
                'faq'       => $posts,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status'    => 'error',
            ], 500);
        }
    }
}