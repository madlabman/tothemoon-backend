<?php

namespace App\Http\Controllers\API;

use App\Balance;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Library\PromoCode;
use App\Mail\EmailVerify;
use App\Repository\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
                ]);
            }
        } catch (JWTException $ex) {
            return response()->json([
                'status'    => 'error',
                'error'     => 'Ошибка входа, повторите позднее.',
            ], 500);
        }

        if (empty(auth()->user()->email_verified)) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'password' => [
                        'Подтвердите адрес электронной почты'
                    ]
                ],
                'msg' => 'Invalid Credentials.'
            ]);
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
        } catch (\Exception $ex) {
            return response()->json([
                'status'    => 'error',
                'error'     => 'Ошибка регистрации, повторите позднее.',
                'ex'        => $ex->getMessage()
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

    /**
     * Check existence of promo code in database
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function promo(Request $request)
    {
        if (!empty($promo = $request->post('promo'))) {
            if (!empty(User::where('promo_code', $promo)->first())) {
                return response()->json([
                    'status'    => 'success',
                    'promo'     => $promo,
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
        ]);
    }

    /**
     * Validate email token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        if (!empty($token = $request->post('token'))) {
            if (!empty($user = User::where('email_token', $token)->first())) {
                $user->email_token = null;
                $user->email_verified = true;
                $user->save();
                return response()->json([
                    'status'    => 'success',
                    'msg'       => 'Почта подтверждена! Войдите.',
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
        ]);
    }
}