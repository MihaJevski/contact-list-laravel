<?php

namespace App\Listeners;

use App\Events\ContactDelete;
use App\Jobs\SendContactDeletedEmailJob;
use App\Mail\ContactDeleted;

class ContactDeleteSendMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ContactDelete $event
     * @return void
     */
    public function handle(ContactDelete $event)
    {
        $job = (new SendContactDeletedEmailJob(new ContactDeleted($event->contact)));

        dispatch($job);
    }
}
