<?php

namespace App\Listeners;

use App\Mail\WelcomeNewContactMail;
use Illuminate\Support\Facades\Mail;

class WelcomeNewContactListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event)
    {
        Mail::to($event->getContact()->email)
            ->send(new WelcomeNewContactMail());
    }
}
