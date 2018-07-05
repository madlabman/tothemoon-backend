<?php

namespace App\Http\Controllers;

use App\Signal;
use Illuminate\Http\Request;

class SignalController extends Controller
{
    protected const PER_PAGE = 12;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $signals = Signal::skip($skip)->take(self::PER_PAGE)->latest()->get();
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
            'info' => 'required|string',
            'is_private' => 'required|boolean'
        ], [
            'level.required' => 'Уровень сигнала обязателен',
            'level.numeric' => 'Неверный формат уровня',
            'info.required' => 'Описание обязательно',
            'is_private.required'    => 'Не установлен тип приватности',
            'is_private.boolean'    => 'Неверный формат типа приватности',
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
                $data['level'] = intval($data['level']);
                $data['is_private'] = (boolean)$data['is_private'];
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
