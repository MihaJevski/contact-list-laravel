<?php

namespace App\Services;

use App\Events\ContactDelete;
use App\Repositories\ContactRepository;

class ContactService
{
    /**
     * @var ContactRepository
     */
    private $repository;

    /**
     * ContactService constructor.
     * @param ContactRepository $repository
     */
    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns all contacts
     *
     * @return mixed
     */
    public function all()
    {
        return $this->repository->all();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->repository->create($data);
    }

    /**
     * @param $data
     * @param $model
     * @return mixed
     */
    public function update($data, $model)
    {
        return $this->repository->update($data, $model);
    }

    /**
     * @param $model
     */
    public function delete($model)
    {
        $this->repository->delete($model);

        ContactDelete::dispatch($model->only(['name', 'email']));
    }
}
