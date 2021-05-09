<?php

namespace App\Providers;

use App\Events\ContactDelete;
use App\Events\NewContactCreatedEvent;
use App\Listeners\ContactDeleteSendMail;
use App\Listeners\WelcomeNewContactListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ContactDelete::class => [
            ContactDeleteSendMail::class
        ],
        NewContactCreatedEvent::class => [
            WelcomeNewContactListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
