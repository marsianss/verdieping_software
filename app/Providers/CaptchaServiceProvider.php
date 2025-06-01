<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Voeg standaard reCAPTCHA sleutels toe als fallback
        // In een productieomgeving zouden deze in het .env bestand moeten staan
        config([
            'captcha.secret' => env('NOCAPTCHA_SECRET', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'),
            'captcha.sitekey' => env('NOCAPTCHA_SITEKEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'),
        ]);
    }
}
