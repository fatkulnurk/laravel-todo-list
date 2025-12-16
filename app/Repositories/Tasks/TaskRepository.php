<?php

namespace App\Repositories\Tasks;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskRepository implements TaskRepositoryInterface
{
    readonly private array $columns;

    public function __construct()
    {
        $this->columns = ['id', 'title', 'description', 'status', 'created_at', 'updated_at'];
    }

    public function getAll(): AnonymousResourceCollection
    {
        return TaskResource::collection(Task::all($this->columns));
    }

    public function create(array $data)
    {
        return Task::query()->create($data)->refresh();
    }

    public function find(int $id)
    {
        return Task::query()->select($this->columns)->findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $task = $this->find($id);
        $task->update($data);
        return $task->refresh();
    }

    public function delete(int $id): void
    {
        $task = $this->find($id);
        $task->delete();
    }
}
