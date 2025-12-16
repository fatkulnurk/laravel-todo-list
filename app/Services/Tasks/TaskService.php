<?php

namespace App\Services\Tasks;

use App\Http\Resources\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\Contracts\TaskServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskService implements TaskServiceInterface
{
    public function __construct(private readonly TaskRepositoryInterface $taskRepository)
    {
    }

    public function getAll(): AnonymousResourceCollection
    {
        return $this->taskRepository->getAll();
    }

    public function create(array $data): TaskResource
    {
        return (new TaskResource($this->taskRepository->create($data)));
    }

    public function find(int $id): TaskResource
    {
        return (new TaskResource($this->taskRepository->find($id)));
    }

    public function update(int $id, array $data): TaskResource
    {
        return (new TaskResource($this->taskRepository->update($id, $data)));
    }

    public function delete(int $id): void
    {
        $this->taskRepository->delete($id);
    }
}
