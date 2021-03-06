<?php

namespace App\Http\Controllers\API;

use App\Device;
use App\Fund;
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
                $fund = Fund::where('slug', 'tothemoon')->first();
                if (empty($fund)) throw new \Exception('Fund doesn\'t exist');

                return response()->json([
                    'status'    => 'success',
                    'balance'   => [
                        'body'  => CryptoPrice::get_base_currencies_from_token(auth()->user()->balance->body),
                        'bonus' => CryptoPrice::get_base_currencies_from_token(auth()->user()->balance->bonus),
                    ]
                ]);
            } else {
//                throw new \Exception('Balance not found.');
                return response()->json([], 500);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'balance' => null,
            ], 500);
        }
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

    /**
     * Return user which has current user as referral.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Return count of referrals of current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ref_count()
    {
        try {
            return response()->json([
                'status' => 'success',
                'ref_count' => count(auth()->user()->referrals),
                'referrals' => auth()->user()->referrals->pluck('name'),
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'ex' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Update profile info for user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Change password of current user.
     *
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Save Firebase Cloud Token to database.
     *
     * @param Request $request
     */
    public function add_device(Request $request)
    {
        try {
            $user = auth()->user();
            $device = Device::updateOrCreate([
                'token' => $request->token,
            ]);
            $user->devices()->save($device);

            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Error in storing device token.'
            ], 500);
        }
    }
}