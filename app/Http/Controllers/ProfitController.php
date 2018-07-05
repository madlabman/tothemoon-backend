<?php

namespace App\Http\Controllers;

use App\Profit;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    protected const PER_PAGE = 12;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $profits = Profit::skip($skip)->take(self::PER_PAGE)->latest()->get();
        $count = Profit::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/profit/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('profit.all')->with([
            'profits'   => $profits,
            'pages'     => $pages,
        ]);
    }

    public function new()
    {
        return view('profit.show');
    }

    public function validateSignalRequest(Request $request)
    {
        \Validator::make($request->all(),[
            'usd_change' => 'required|numeric',
        ], [
            'usd_change.required' => 'Сумма обязательна',
            'usd_change.numeric' => 'Неверный формат суммы',
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
            $profit->usd_change = $request->post('usd_change');
            $profit->save();
            $request->session()->flash('status', 'Сумма обновлена!');
        }

        return redirect()->back();
    }
}
