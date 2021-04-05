<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $model);

    public function delete($model);
}
