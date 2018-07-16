<?php

namespace App\Http\Controllers;

use App\FAQPost;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    protected const PER_PAGE = 12;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $posts = FAQPost::skip($skip)->take(self::PER_PAGE)->latest()->get();
        $count = FAQPost::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/faq/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('faq.all')->with([
            'posts'    => $posts,
            'pages'    => $pages,
        ]);
    }

    public function delete($id)
    {
        $post = FAQPost::find($id);
        if (!empty($post)) {
            $post->delete();
            \request()->session()->flash('status', 'Запись удалена!');
        }

        return redirect('faq');
    }

    public function validatePostRequest(Request $request)
    {
        return $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ], [
            'question.required' => 'Вопрос обязателен',
            'answer.required' => 'Ответ обязателен',
        ]);
    }

    public function edit($id)
    {
        $post = FAQPost::find($id);
        if ($post == null) return redirect()->back();
        return view('faq.show')->with(compact('post'));
    }

    public function update(Request $request, $id)
    {
        if ($data = $this->validatePostRequest($request)) {
            $post = FAQPost::find($id);
            if (!empty($post)) {
                $post->update($data);
            }
        }
        $request->session()->flash('status', 'Запись обновлена!');
        return redirect()->back();
    }

    public function new()
    {
        return view('faq.show');
    }

    public function create(Request $request)
    {
        if ($data = $this->validatePostRequest($request)) {
            $post = FAQPost::create($data);
            $request->session()->flash('status', 'Запись создана!');
            return redirect('/faq/edit/' . $post->id);
        }

        return redirect()->back();
    }

}
