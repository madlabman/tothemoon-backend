<?php

namespace App\Http\Controllers;

use App\Withdraw;
use Illuminate\Http\Request;

class WithdrawsController extends Controller
{
    protected const PER_PAGE = 10;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $withdraws = Withdraw::skip($skip)->take(self::PER_PAGE)->latest()->get();
        $count = Withdraw::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/withdraws/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('withdraws.all')->with([
            'withdraws'     => $withdraws,
            'pages'         => $pages,
        ]);
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
