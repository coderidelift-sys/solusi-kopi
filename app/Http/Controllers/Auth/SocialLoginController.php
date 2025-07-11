<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }

        $existingUser = User::where('google_id', $user->id)->first();

        if ($existingUser) {
            Auth::login($existingUser, true);
        } else {
            // Check if user exists with the same email but without Google ID
            $existingUserByEmail = User::where('email', $user->getEmail())->first();

            if ($existingUserByEmail) {
                // Update existing user with Google ID
                $existingUserByEmail->google_id = $user->id;
                $existingUserByEmail->save();
                Auth::login($existingUserByEmail, true);
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'google_id' => $user->id,
                    'password' => Hash::make(Str::random(16)), // Generate a random password
                    'email_verified_at' => now(),
                ]);
                Auth::login($newUser, true);
            }
        }

        return redirect()->intended('/dashboard'); // Sesuaikan dengan halaman setelah login
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Facebook: ' . $e->getMessage());
        }

        $existingUser = User::where('facebook_id', $user->id)->first();

        if ($existingUser) {
            Auth::login($existingUser, true);
        } else {
            // Check if user exists with the same email but without Facebook ID
            $existingUserByEmail = User::where('email', $user->getEmail())->first();

            if ($existingUserByEmail) {
                // Update existing user with Facebook ID
                $existingUserByEmail->facebook_id = $user->id;
                $existingUserByEmail->save();
                Auth::login($existingUserByEmail, true);
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'facebook_id' => $user->id,
                    'password' => Hash::make(Str::random(16)), // Generate a random password
                    'email_verified_at' => now(),
                ]);
                Auth::login($newUser, true);
            }
        }

        return redirect()->intended('/dashboard'); // Sesuaikan dengan halaman setelah login
    }
}
