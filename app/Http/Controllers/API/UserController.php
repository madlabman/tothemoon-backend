<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Library\CryptoPrice;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Return list of all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function users()
    {
        try {
            return response()->json([
                'users' => User::all()
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Return balance of current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_balance()
    {
        try {
            if (!empty(auth()->user()->balance)) {
                $btc = round( auth()->user()->balance->body, 3 );
                $usd = CryptoPrice::convert($btc, 'btc', 'usd');
                $rub = CryptoPrice::convert($btc, 'btc', 'rub');

                return response()->json([
                    'status'    => 'success',
                    'balance'   => [
                        'btc'   => $btc,
                        'usd'   => $usd,
                        'rub'   => $rub,
                    ]
                ]);
            } else {
                throwException(new \Exception('Balance not found.'));
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'balance' => null,
                'error' => $ex->getMessage(),
            ], 500);
        }

        return response()->json([], 500);
    }

    /**
     * Return basic info about user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => auth()->user(),
            ]);
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function leader()
    {
        try {
            return response()->json([
                'status' => 'success',
                'leader' => auth()->user()->leader,
            ]);
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function ref_count()
    {
        try {
            return response()->json([
                'status' => 'success',
                'ref_count' => count(auth()->user()->referrals)
            ]);
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            if (empty($request->post('name'))) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'name'  => [
                            'Введите имя'
                        ]
                    ]
                ]);
            }

            $user = auth()->user();
            $user_data = [];
            $user_data['name'] = $request->post('name');

            // Update user data
            $user->update($user_data);

            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = auth()->user();
            if (Hash::check($request->post('old'), $user->getAuthPassword())) {
                $password = bcrypt($request->post('new'));
                $user->password = $password;
                $user->save();

                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'old'  => [
                            'Неверный пароль'
                        ]
                    ]
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }
}