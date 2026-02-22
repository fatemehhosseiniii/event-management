<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\VerifyRequest;
use App\Models\User;
use App\Services\Auth\AuthenticateService;
use App\Services\Response;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


#[OA\Tag(name: 'Authentication', description: 'Authentication endpoints')]
class AuthenticateController extends Controller
{
    #[OA\Post(
        path: '/authenticate',
        summary: 'Login - Send OTP code',
        description: 'Send OTP verification code to user email',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OTP code sent successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/LoginResponse')
            ),
            new OA\Response(
                response: 429,
                description: 'Too many requests - OTP already sent',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            )
        ]
    )]
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

    #[OA\Post(
        path: '/authenticate/verify',
        summary: 'Verify OTP code',
        description: 'Verify OTP code and get access token',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/VerifyRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OTP verified successfully - Access token returned',
                content: new OA\JsonContent(ref: '#/components/schemas/VerifyResponse')
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid OTP code or expired',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
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
