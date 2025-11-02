<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * @return RedirectResponse
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => bcrypt(str()->random(32)),
                ]
            );

            if (!$user->hasAnyRole(['admin', 'user'])) {
                $user->assignRole('user');
            }

            Auth::login($user, true);

            return redirect()->route('dashboard');
        } catch (Exception) {
            return redirect()->route('login')->with('error', 'Unable to login with Google. Please try again.');
        }
    }

    /**
     * @return RedirectResponse
     */
    public function redirectToFacebook(): RedirectResponse
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * @return RedirectResponse
     */
    public function handleFacebookCallback(): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            $email = $socialUser->getEmail() ?? $socialUser->getId() . '@facebook.com';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $socialUser->getName() ?? 'Facebook User',
                    'password' => bcrypt(str()->random(32)),
                ]
            );

            if (!$user->hasAnyRole(['admin', 'user'])) {
                $user->assignRole('user');
            }

            Auth::login($user, true);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to login with Facebook. Please try again.');
        }
    }
}

