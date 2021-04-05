<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $token;

    /**
     * UserResource constructor.
     *
     * @param $resource
     * @param $token
     */
    public function __construct($resource, $token)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "username" => $this->name,
            "role" => $this->role,
            "auth_key" => $this->token,
            'authorizedToCreateContact' => $this->can('create', [Contact::class]),
        ];
    }
}
