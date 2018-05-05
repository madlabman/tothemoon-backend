<?php

namespace App\Http\Controllers\API;

use App\Balance;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('login', 'password');

        try {
            if ( !$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'password' => [
                            'Пользователь с таким логином или паролем не найден'
                        ]
                    ],
                    'msg' => 'Invalid Credentials.'
                ], 400);
            }
        } catch (JWTException $ex) {
            return response()->json([
                'status'    => 'error',
                'error'     => 'Ошибка входа, повторите позднее.',
            ], 500);
        }

//        Redis::set(auth()->user()->getAuthIdentifier(), $token);

//        try {
//            $tokenRepository = app()->make(TokenRepository::class);
//            $apiToken = new APIToken();
//            $apiToken->setToken($token);
//            $apiToken->setUser(auth()->user());
//            $tokenRepository->save($apiToken);
//        } catch (\Exception $ex) {
//            return response()->json([
//                'status'    => 'error',
//                'error'     => 'Ошибка входа, повторите позднее.',
//            ], 500);
//        }

        return response()->json([
            'status'    => 'success',
        ])->header('Authorization', 'Bearer ' . $token)
            ->header('access-control-expose-headers', 'Authorization');
    }

    public function register(RegisterFormRequest $request)
    {
        try {
            // Creating user
            $user = new User();
            $user->uuid = Uuid::uuid1();
            $user->name = $request->post('name');
            $user->login = $request->post('username');
            $user->phone = $request->post('phone');
            $user->password = bcrypt($request->post('password'));
            $user->save();

            $balance = new Balance();
            $user->balance()->save($balance);
        } catch (\Exception $ex) {
            return response()->json([
                'status'    => 'error',
                'error'     => 'Ошибка регистрации, повторите позднее.',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();
        JWTAuth::invalidate();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Successfully logged out'
        ]);
    }
}