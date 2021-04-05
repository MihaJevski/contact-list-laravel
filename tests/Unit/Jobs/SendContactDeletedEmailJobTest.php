<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SendContactDeletedEmailJob;
use App\Mail\ContactDeleted;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Queue;
use Tests\CreatesApplication;

class SendContactDeletedEmailJobTest extends TestCase
{
    use CreatesApplication;

    /** @test */
    public function it_sends_email_into_queue()
    {
        Queue::fake();

        $contact = [
            'name' => 'Test',
            'email' => 'test@example.com'
        ];

        $mailable = new ContactDeleted($contact);

        $job = (new SendContactDeletedEmailJob($mailable));
        dispatch($job);

        Queue::assertPushed(SendContactDeletedEmailJob::class, function($job) {
            return $job->mailable instanceof ContactDeleted;
        });
    }
}
