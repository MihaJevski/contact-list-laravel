<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return Contact::class;
    }
}
