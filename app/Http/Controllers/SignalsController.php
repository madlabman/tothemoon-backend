<?php

namespace App\Http\Controllers;

use App\Signal;
use Illuminate\Http\Request;

class SignalsController extends Controller
{
    protected const PER_PAGE = 10;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $signals = Signal::skip($skip)->take(self::PER_PAGE)->get();
        $count = Signal::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/signals/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('signals.all')->with([
            'signals'     => $signals,
            'pages'     => $pages,
        ]);
    }

    public function validateSignalRequest(Request $request)
    {
        return $request->validate([
            'level' => 'required|numeric',
            'info' => 'required|string'
        ], [
            'level.required' => 'Уровень сигнала обязателен',
            'level.numeric' => 'Неверный формат уровня',
            'info.required' => 'Описание обязательно',
        ]);
    }

    public function edit($id)
    {
        $signal = Signal::find($id);
        if ($signal == null) return redirect()->back();
        return view('signals.show')->with(compact('signal'));
    }

    public function update(Request $request, $id)
    {
        if ($data = $this->validateSignalRequest($request)) {
            $signal = Signal::find($id);
            if (!empty($signal)) {
                $signal->update($data);
            }
        }
        $request->session()->flash('status', 'Сигнал обновлен!');
        return redirect()->back();
    }

    public function new()
    {
        return view('signals.show');
    }

    public function create(Request $request)
    {
        if ($data = $this->validateSignalRequest($request)) {
            $signal = Signal::create($data);
            $request->session()->flash('status', 'Сигнал создан!');
            return redirect('/signals/edit/' . $signal->id);
        }

        return redirect()->back();
    }

    public function delete($id)
    {
        $signal = Signal::find($id);
        if (!empty($signal)) {
            $signal->delete();
        }
        \request()->session()->flash('status', 'Сигнал удален!');
        return redirect('signals');
    }
}
