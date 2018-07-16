<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function validatePostRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ], [
            'title.required' => 'Заголовок обязателен',
            'content.required' => 'Текст обязателен',
        ]);
    }

    public function command()
    {
        $post = Page::where('slug', 'command')->first();
        if ($post == null) return redirect()->back();
        return view('pages.show')->with(compact('post'));
    }

    public function update_command(Request $request)
    {
        if ($data = $this->validatePostRequest($request)) {
            $post = Page::where('slug', 'command')->first();
            if (!empty($post)) {
                $post->update($data);
            }
        }
        $request->session()->flash('status', 'Страница обновлена!');
        return redirect()->back();
    }

}
