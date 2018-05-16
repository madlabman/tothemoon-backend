<?php

namespace App\Http\Controllers;

use App\Withdraw;
use Illuminate\Http\Request;

class WithdrawsController extends Controller
{
    public function index()
    {
        $withdraws = Withdraw::all();
        return view('withdraws.all')->with(compact('withdraws'));
    }

    public function delete($id)
    {
        $withdraw = Withdraw::find($id);
        if (!empty($withdraw)) {
            $withdraw->delete();
            \request()->session()->flash('status', 'Выплата удалена!');
        }

        return redirect('withdraws');
    }

    public function confirm($id)
    {
        $withdraw = Withdraw::find($id);
        if (!empty($withdraw)) {
            $withdraw->is_confirmed = true;
            $withdraw->save();
            \request()->session()->flash('status', 'Выплата подтверждена!');
        }

        return redirect('withdraws');
    }
}
