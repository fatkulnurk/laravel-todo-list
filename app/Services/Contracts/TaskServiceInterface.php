<?php

namespace App\Services\Contracts;

use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface TaskServiceInterface
{
    public function getAll(): AnonymousResourceCollection;

    public function create(array $data): TaskResource;

    public function find(int $id): TaskResource;

    public function update(int $id, array $data): TaskResource;

    public function delete(int $id): void;
}
