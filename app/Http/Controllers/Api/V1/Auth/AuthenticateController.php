<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\VerifyRequest;
use App\Models\User;
use App\Services\Auth\AuthenticateService;
use App\Services\Response;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


class AuthenticateController extends Controller
{
    
    public function login(LoginRequest $request): JsonResponse
    {
        //Find User By Mobile Number
        $user = User::where('email', $request->validated('email'))->first();
        if(!$user)
            $user = User::create(['email' => $request->validated('email')]);
     
        //check Expired OTP code
        if ($user && (empty($user->expired_at) || $user->expired_at <= now())) {
            //make otp and update user
            $makeOtp=AuthenticateService::sendVerifyCode($user);
            $user->update([
                'otp' => $makeOtp,
                'expired_at' => now()->addMinutes(2)
            ]);
            
        } elseif (!empty($user->expired_at) && $user->expired_at > now()) {
            $second = now()->diffInSeconds($user->expired_at);
            return Response::error(__('auth.throttle', ['second' => ceil($second)]), 429);
        }

        return Response::success($request->validated());
    }

    public function verify(VerifyRequest $request): JsonResponse
    {
        $data=$request->validated();

        //Find User By Email
        $user = User::where('email', $data['email'])->first();
        if (!$user)
            return Response::error(__('auth.failed'), HttpResponse::HTTP_NOT_FOUND);

        //check valid data
        if (!$user || ($user && !Hash::check($data['code'], $user->otp)))
            return Response::error(__('auth.failed'), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        if ($user->expired_at <= now())
            return Response::error(__('auth.expired code'), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        //update user
        $user->update([
            'otp' => null,
            'expired_at' => null
        ]);
        
        
        return Response::success(['access_token' => $user->createToken('auth_token')->plainTextToken]);
    }
}
