<?php

namespace App\Http\Controllers;

use App\Balance;
use App\Fund;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\UpdateUserRequest;
use App\LevelCondition;
use App\Library\PromoCode;
use App\Mail\EmailVerify;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    protected const PER_PAGE = 12;

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
                    'text' => $i,
                    'link' => url('/users/?page=' . $i),
                    'active' => $request->get('page') == $i
                ]);
            }
        }

        return view('users.all')->with([
            'users' => $users->map(function ($user) {
                return (object)[
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'balance' => (object)[
                        'body' => $user->balance->body,
                        'bonus' => $user->balance->bonus,
                    ],
                ];
            })->all(),
            'pages' => $pages,
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (empty($user)) return app()->abort(404);
        $levels = LevelCondition::orderBy('min_usd_amount')->orderBy('max_duration')->get();
        return view('users.show')->with([
            'user' => $user,
            'levels' => $levels,
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

    public function update_balance(Request $request)
    {
        $user = User::findOrFail($request->user);
//        dd($request->all());
        $user->balance->body = (float)$request->body;
        $user->balance->bonus = (float)$request->bonus;
        $user->balance->save();

        $request->session()->flash('status', 'Пользователь обновлен!');
        return redirect()->back();
    }


    public function new() {
        return view('users.new');
    }

    public function create(RegisterFormRequest $request)
    {
        // Creating user
        $user = new User();
        $user->uuid = Uuid::uuid1();
        $user->name = $request->post('name');
        $user->login = $request->post('login');
        $user->phone = $request->post('phone');
        $user->password = bcrypt($request->post('password'));
        $user->email = $request->post('email');

        // Generate promo_code
        do {
            $promo_code = PromoCode::generate(8);
        } while (User::where('promo_code', $promo_code)->first() !== null);
        $user->promo_code = $promo_code;

        $user->email_verified = false;
        $user->email_token = Uuid::uuid1();
        // Save user
        $user->save();

        // Check promo
        if (!empty($promo = $request->post('promo'))) {
            $promo_owner = User::where('promo_code', $promo)->first();
            if (!empty($promo_owner)) {
                $promo_owner->referrals()->save($user);
            }
        }

        $balance = new Balance();
        $user->balance()->save($balance);

        // Send email
        Mail::to($user)->send(new EmailVerify($user->email_token));

        return redirect()->to('/users/edit/' . $user->id);
    }
}
