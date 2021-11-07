<?php

namespace App\Http\Controllers;

use App\Events\LoggedIn;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserOtpRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Device;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        event(new Registered($user = User::make($request->all())));
        $user->password = Hash::make($request->get('password'));
        if (!$user->save()) {
            return abort(500);
        }
        $token = $user->createToken('auth', ['otp']);
        $device = Device::make([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('user-agent')
        ]);
        event(new LoggedIn($user, $device));
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }

    public function login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->get('email'))->first();
        if (!$user->exists() || !Hash::check($request->get('password'), $user?->password)) return abort(422);
        $token = $user->createToken('auth', ['otp']);
        $device = Device::make([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('user-agent')
        ]);
        event(new LoggedIn($user, $device));
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }

    public function logout(Request $request)
    {
        return $request;//TODO
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function otp(UserOtpRequest $request)
    {
        $user = $request->user();
        if ($user->otp->code === $request->get('code')) { //TODO: we can also check valid time for an otp
            $token = $user->createToken('auth');
            return response()->json([
                'status' => 'ok',
                'token' => $token
            ], 201);
        } else {
            $device = Device::make([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('user-agent')
            ]);
            event(new LoggedIn($user, $device));
            return response()->json([
                'status' => 'error',
                'message' => __('could not verify otp code')
            ], 422);
        }
    }
}
