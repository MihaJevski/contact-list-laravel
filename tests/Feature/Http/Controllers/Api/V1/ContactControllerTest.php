<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Events\NewContactCreatedEvent;
use App\Mail\WelcomeNewContactMail;
use App\Models\Contact;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * @group api_controller
 */
class ContactControllerTest extends TestCase
{
    /**
     * @var string
     */
    private $url;

    public function setUp(): void
    {
        parent::setUp();
        $this->url = '/api/v1/contacts/';
    }

    /** @test */
    public function it_should_forbid_an_unauthenticated_user_to_retrieve_all_contacts()
    {
        $response = $this->getJson($this->url);

        $response
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_should_forbid_an_unauthenticated_user_to_retrieve_one_contacts()
    {
        $contact = create(Contact::class);

        $response = $this->getJson($this->url . $contact->id);

        $response
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_returns_all_contacts()
    {
        $this->actingAsAdmin();

        create(Contact::class, [], 5);

        $response = $this->getJson($this->url);

        $response->assertOk();

        $this->assertCount(5, $response->getData());
    }

    /** @test */
    public function it_returns_one_contact_for_admin()
    {
        $this->actingAsAdmin();

        $contact = create(Contact::class);

        $response = $this->getJson($this->url . $contact->id);

        $response->assertOk()
            ->assertExactJson([
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'canBeUpdated' => true,
                'canBeDeleted' => true,
            ]);
    }

    /** @test */
    public function it_returns_one_contact_for_moderator()
    {
        $this->actingAsModerator();

        $contact = create(Contact::class);

        $response = $this->getJson($this->url . $contact->id);

        $response->assertOk()
            ->assertExactJson([
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'canBeUpdated' => true,
                'canBeDeleted' => false,
            ]);
    }

    /** @test */
    public function it_returns_one_contact_for_viewer()
    {
        $this->actingAsViewer();

        $contact = create(Contact::class);

        $response = $this->getJson($this->url . $contact->id);

        $response->assertOk()
            ->assertExactJson([
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'canBeUpdated' => false,
                'canBeDeleted' => false,
            ]);
    }

    /** @test */
    public function it_returns_error_when_contact_not_found()
    {
        $this->actingAsViewer();

        $response = $this->getJson($this->url . 1);

        $response->assertNotFound()
            ->assertExactJson([
                'error' => 'Resource not found',
            ]);
    }

    /** @test */
    public function admin_can_create_contact()
    {
        $this->actingAsAdmin();

        $payload = make(Contact::class)->toArray();

        $response = $this->postJson($this->url, $payload);

        $response->assertCreated()
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'canBeUpdated' => true,
                    'canBeDeleted' => true,
                ],
                'message' => 'Contact successfully created'
            ]);
    }

    /** @test */
    public function an_event_is_fired_when_admin_creates_contact()
    {
        $this->actingAsAdmin();

        $payload = make(Contact::class)->toArray();

        Event::fake([NewContactCreatedEvent::class]);

        $this->postJson($this->url, $payload);

        Event::assertDispatched(NewContactCreatedEvent::class);
        Event::assertDispatchedTimes(NewContactCreatedEvent::class);
    }

    /** @test */
    public function a_welcome_message_is_sent_when_admin_creates_contact()
    {
        $this->withoutExceptionHandling();

        $this->actingAsAdmin();

        $payload = make(Contact::class)->toArray();

        Mail::fake();

        $this->postJson($this->url, $payload);

        Mail::assertQueued(WelcomeNewContactMail::class, 1);
        Mail::assertNothingSent();
    }

    /** @test */
    public function moderator_can_create_contact()
    {
        $this->actingAsModerator();

        $payload = make(Contact::class)->toArray();

        $response = $this->postJson($this->url, $payload);

        $response->assertCreated()
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'canBeUpdated' => true,
                    'canBeDeleted' => false,
                ],
                'message' => 'Contact successfully created'
            ]);
    }

    /** @test */
    public function viewer_can_not_create_contact()
    {
        $this->actingAsViewer();

        $payload = make(Contact::class)->toArray();

        $response = $this->postJson($this->url, $payload);

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_not_update_not_existing_contact()
    {
        $this->actingAsAdmin();

        $payload = make(Contact::class)->toArray();

        $response = $this->putJson($this->url . 1, $payload);

        $response->assertNotFound()
            ->assertExactJson([
                'error' => 'Resource not found',
            ]);
    }

    /** @test */
    public function admin_can_update_contact()
    {
        $this->actingAsAdmin();

        $contact = create(Contact::class);

        $payload = make(Contact::class)->toArray();

        $response = $this->putJson($this->url . $contact->id, $payload);

        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $contact->id,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'canBeUpdated' => true,
                    'canBeDeleted' => true,
                ],
                'message' => 'Contact successfully updated'
            ]);
    }

    /** @test */
    public function moderator_can_update_contact()
    {
        $this->actingAsModerator();

        $contact = create(Contact::class);

        $payload = make(Contact::class)->toArray();

        $response = $this->putJson($this->url . $contact->id, $payload);

        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $contact->id,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'canBeUpdated' => true,
                    'canBeDeleted' => false,
                ],
                'message' => 'Contact successfully updated'
            ]);
    }

    /** @test */
    public function viewer_can_not_update_contact()
    {
        $this->actingAsViewer();

        $contact = create(Contact::class);

        $payload = make(Contact::class)->toArray();

        $response = $this->putJson($this->url . $contact->id, $payload);

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_not_delete_not_existing_contact()
    {
        $this->actingAsAdmin();

        $response = $this->deleteJson($this->url . 1);

        $response->assertNotFound()
            ->assertExactJson([
                'error' => 'Resource not found',
            ]);
    }

    /** @test */
    public function admin_can_delete_contact()
    {
        $this->actingAsAdmin();

        $contact1 = create(Contact::class);
        $contact2 = create(Contact::class);

        $response = $this->deleteJson($this->url . $contact1->id);

        $response->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $contact2->id,
                        'name' => $contact2->name,
                        'email' => $contact2->email,
                        'canBeUpdated' => true,
                        'canBeDeleted' => true,
                    ]
                ],
                'message' => 'Contact successfully deleted'
            ]);
    }

    /** @test */
    public function moderator_can_not_delete_contact()
    {
        $this->actingAsModerator();

        $contact1 = create(Contact::class);
        $contact2 = create(Contact::class);

        $response = $this->deleteJson($this->url . $contact1->id);

        $response->assertForbidden();

        $additionalResponse = $this->getJson($this->url);

        $additionalResponse->assertOk()
            ->assertExactJson([
                [
                    'id' => $contact1->id,
                    'name' => $contact1->name,
                    'email' => $contact1->email,
                    'canBeUpdated' => true,
                    'canBeDeleted' => false,
                ],
                [
                    'id' => $contact2->id,
                    'name' => $contact2->name,
                    'email' => $contact2->email,
                    'canBeUpdated' => true,
                    'canBeDeleted' => false,
                ],
            ]);
    }

    /** @test */
    public function viewer_can_not_delete_contact()
    {
        $this->actingAsViewer();

        $contact1 = create(Contact::class);
        $contact2 = create(Contact::class);

        $response = $this->deleteJson($this->url . $contact1->id);

        $response->assertForbidden();

        $additionalResponse = $this->getJson($this->url);

        $additionalResponse->assertOk()
            ->assertExactJson([
                [
                    'id' => $contact1->id,
                    'name' => $contact1->name,
                    'email' => $contact1->email,
                    'canBeUpdated' => false,
                    'canBeDeleted' => false,
                ],
                [
                    'id' => $contact2->id,
                    'name' => $contact2->name,
                    'email' => $contact2->email,
                    'canBeUpdated' => false,
                    'canBeDeleted' => false,
                ],
            ]);
    }
}
