<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;

class ContactController extends Controller
{
    /**
     * @var ContactService
     */
    private $service;

    /**
     * ContactController constructor.
     * @param ContactService $service
     */
    public function __construct(ContactService $service)
    {
        $this->service = $service;
        $this->authorizeResource(Contact::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ContactResource::collection($this->service->all());
    }

    /**
     * Display the specified resource.
     *
     * @param Contact $contact
     * @return ContactResource
     */
    public function show(Contact $contact): ContactResource
    {
        return new ContactResource($contact);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactFormRequest $request
     * @return ContactResource
     */
    public function store(ContactFormRequest $request): ContactResource
    {
        return (new ContactResource($this->service->create($request->all())))
            ->additional(['message' => 'Contact successfully created']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactFormRequest $request
     * @param Contact $contact
     * @return ContactResource
     */
    public function update(ContactFormRequest $request, Contact $contact): ContactResource
    {
        $model = $this->service->update($request->all(), $contact);

        return (new ContactResource($model))
            ->additional(['message' => 'Contact successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function destroy(Contact $contact): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $this->service->delete($contact);

        return ContactResource::collection($this->service->all())
            ->additional(['message' => 'Contact successfully deleted']);
    }
}
