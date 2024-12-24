<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Support\Carbon;
use Spatie\GoogleCalendar\Event as SpatieEvent;
use Spatie\GoogleCalendar\GoogleCalendar;

class Event extends SpatieEvent
{
    protected static function getGoogleCalendar(?string $calendarId = null): GoogleCalendar
    {
        return app(GoogleCalendar::class, ['calendarId' => $calendarId]);
    }

    public function getStartDateTime(): Carbon
    {
        return new Carbon($this->googleEvent->start->dateTime)
            ->timezone($this->googleEvent->start->timeZone);
    }

    public function getEndDateTime(): Carbon
    {
        return new Carbon($this->googleEvent->end->dateTime)
            ->timezone($this->googleEvent->end->timeZone);
    }
}
