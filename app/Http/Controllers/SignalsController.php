<?php

namespace App\Http\Controllers;

use App\Signal;
use Illuminate\Http\Request;

class SignalsController extends Controller
{
    public function index()
    {
        return view('signals.all')->with('signals', Signal::all());
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
