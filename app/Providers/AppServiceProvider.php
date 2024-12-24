<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Spatie\GoogleCalendar\GoogleCalendar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Model::shouldBeStrict();

        $this->app->bind(GoogleCalendar::class, function ($app, $params) {
            $calendarId = $params['calendarId'] ?? env('GOOGLE_CALENDAR_ID');

            $token = Auth::user()->google_token;

            $client = new \Google\Client;
            $client->setAccessToken($token);

            $service = new \Google_Service_Calendar($client);

            return new GoogleCalendar($service, $calendarId);
        });
    }
}
