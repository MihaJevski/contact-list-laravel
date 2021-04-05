<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    /**
     * Create a new message instance.
     *
     * @param $contact
     */
    public function __construct(array $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Contact was deleted';

        return $this
            ->subject($subject)
            ->to(config('mail.receiver.address'))
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('emails.contact.deleted');
    }
}
