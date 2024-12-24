<?php

use App\Models\User;
use App\ValueObjects\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')
        ->scopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events',
        ])
        ->redirect();
});

Route::get('/auth/callback', function (): void {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::where('email', $googleUser->email)->first();

    if (! $user) {
        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => Hash::make(rand(100000, 999999)),
        ]);
    }

    Auth::login($user, true);

    $user->update([
        'google_token' => $googleUser->token,
        'google_refresh_token' => $googleUser->refreshToken,
        'google_token_valid_until' => now()->addSeconds($googleUser->expiresIn),
    ]);

    // Use $user->token to access Google Calendar API
});

Route::get('/test', function (): void {

    $firstEvent = Event::get()->first();

    dd($firstEvent->getStartDateTime());

});

Route::get('/', function () {
    return view('welcome');
});
