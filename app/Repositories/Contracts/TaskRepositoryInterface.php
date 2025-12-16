<?php

namespace App\Repositories\Contracts;

use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface TaskRepositoryInterface
{
    public function getAll(): AnonymousResourceCollection;

    public function create(array $data);

    public function find(int $id);

    public function update(int $id, array $data);

    public function delete(int $id): void;
}
