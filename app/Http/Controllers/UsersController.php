<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Profit;
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
        return view('users.all')->with([
            'users'     => $users,
            'pages'     => $pages,
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (empty($user)) return app()->abort(404);
        return view('users.show')->with(compact('user'));
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
