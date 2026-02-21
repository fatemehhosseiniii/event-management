<?php   

namespace App\Services\Auth;

use App\Notifications\VerifyNotification;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticateService
{
    public static function sendVerifyCode($user): int
    {
        $code = self::generateRandomNumber();

        $user->notify(new VerifyNotification($code));

        return $code;
    }

    public static function generateRandomNumber(): int
    {
        if (config('app.env') == 'local') {
            return 123456;
        }
        return mt_rand(100000, 999999);
    }
}