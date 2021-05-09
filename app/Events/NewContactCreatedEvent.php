<?php

namespace App\Events;

use App\Models\Contact;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewContactCreatedEvent
{
    use Dispatchable, SerializesModels;

    protected $contact;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Getter for contact
     *
     * @return Contact
     */
    public function getContact(): Contact
    {
        return $this->contact;
    }
}
