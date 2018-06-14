<?php

namespace App\Http\Controllers;

use App\Fund;
use App\Http\Requests\UpdateUserRequest;
use App\LevelCondition;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected const PER_PAGE = 10;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $users = User::skip($skip)->take(self::PER_PAGE)->where('login', '<>', auth()->user()->login)->latest()->get();
        $count = User::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/users/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }

        $token_in_usd = 0;
        $fund = Fund::where('slug', 'tothemoon')->first();
        if (!empty($fund)) $token_in_usd = $fund->token_price;

        return view('users.all')->with([
            'users'     => $users->map(function ($user) use ($token_in_usd) {
                return (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'balance' => (object)[
                        'body'  => $user->balance->body * $token_in_usd,
                        'bonus' => $user->balance->bonus * $token_in_usd,
                    ],
                ];
            })->all(),
            'pages'     => $pages,
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (empty($user)) return app()->abort(404);
        $levels = LevelCondition::orderBy('min_usd_amount')->orderBy('max_duration')->get();
        return view('users.show')->with([
            'user'      => $user,
            'levels'    => $levels,
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            $user->delete();
            \request()->session()->flash('status', 'Пользователь удален!');
        }

        return redirect('users');
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = User::find($id);
        if (!empty($user)) {
            $user->update($request->all());
            $request->session()->flash('status', 'Пользователь обновлен!');
        }

        return redirect()->back();
    }
}
