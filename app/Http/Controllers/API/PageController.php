<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Page;

class PageController extends Controller
{
    public function index($slug)
    {
        try {
            if (!empty($slug)) {
                $page = Page::where('slug', $slug)->first();
                if (!empty($page)) {
                    return response()->json([
                        'status'    => 'success',
                        'page'      => $page,
                    ]);
                }
            }
            throw new \Exception('Page not found');
        } catch (\Exception $ex) {
            return response()->json([
                'status'    => 'error',
            ], 500);
        }
    }
}