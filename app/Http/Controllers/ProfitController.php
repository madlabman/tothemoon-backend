<?php

namespace App\Http\Controllers;

use App\Profit;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index()
    {
        return view('profit.all')->with('profits', Profit::all());
    }

    public function new()
    {
        return view('profit.show');
    }

    public function validateSignalRequest(Request $request)
    {
        \Validator::make($request->all(),[
            'amount' => 'required|numeric',
        ], [
            'amount.required' => 'Сумма обязательна',
            'amount.numeric' => 'Неверный формат суммы',
        ])->validate();

//        return $request->validate([
//            'amount' => 'required|numeric',
//        ], [
//            'amount.required' => 'Сумма обязательна',
//            'amount.numeric' => 'Неверный формат суммы',
//        ]);
    }

    public function create(Request $request)
    {
        $this->validateSignalRequest($request);
        unset($request->_token);
        $profit = Profit::create($request->all());
        $request->session()->flash('status', 'Значение добавлено!');
        return redirect('/profit/edit/' . $profit->id);
    }

    public function edit($id)
    {
        $profit = Profit::find($id);
        if (empty($profit)) return redirect()->back();
        return view('profit.show')->with(compact('profit'));
    }

    public function delete($id)
    {
        $profit = Profit::find($id);
        if (!empty($profit)) {
            $profit->delete();
            \request()->session()->flash('status', 'Значение удалено!');
        }

        return redirect('profit');
    }

    public function update($id, Request $request)
    {
        $this->validateSignalRequest($request);

        $profit = Profit::find($id);
        if (!empty($profit)) {
            $profit->amount = $request->post('amount');
            $profit->save();
            $request->session()->flash('status', 'Сумма обновлена!');
        }

        return redirect()->back();
    }
}
