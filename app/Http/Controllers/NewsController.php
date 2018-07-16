<?php

namespace App\Http\Controllers;

use App\NewsPost;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected const PER_PAGE = 12;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $payments = NewsPost::skip($skip)->take(self::PER_PAGE)->latest()->get();
        $count = NewsPost::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/news/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('news.all')->with([
            'news'     => $payments,
            'pages'    => $pages,
        ]);
    }

    public function delete($id)
    {
        $post = NewsPost::find($id);
        if (!empty($post)) {
            $post->delete();
            \request()->session()->flash('status', 'Новость удалена!');
        }

        return redirect('news');
    }

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

    public function edit($id)
    {
        $post = NewsPost::find($id);
        if ($post == null) return redirect()->back();
        return view('news.show')->with(compact('post'));
    }

    public function update(Request $request, $id)
    {
        if ($data = $this->validatePostRequest($request)) {
            $post = NewsPost::find($id);
            if (!empty($post)) {
                $post->update($data);
            }
        }
        $request->session()->flash('status', 'Новость обновлена!');
        return redirect()->back();
    }

    public function new()
    {
        return view('news.show');
    }

    public function create(Request $request)
    {
        if ($data = $this->validatePostRequest($request)) {
            $post = NewsPost::create($data);
            $request->session()->flash('status', 'Новость создана!');
            return redirect('/news/edit/' . $post->id);
        }

        return redirect()->back();
    }

}
