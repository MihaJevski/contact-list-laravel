<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactDelete
{
    use Dispatchable, SerializesModels;

    /**
     * @var array
     */
    public $contact;

    /**
     * Create a new event instance.
     *
     * @param array $contact
     */
    public function __construct(array $contact)
    {
        $this->contact = $contact;
    }
}
