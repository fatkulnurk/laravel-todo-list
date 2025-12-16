<?php

namespace Tests\Unit\Repositories\Tasks;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected TaskRepositoryInterface $taskRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = app(TaskRepositoryInterface::class);
    }

    public function test_can_create_a_task()
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Description',
            'status' => TaskStatus::Pending->value,
        ];

        $task = $this->taskRepository->create($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_can_find_a_task_by_id()
    {
        $createdTask = Task::factory()->create();

        $task = $this->taskRepository->find($createdTask->id);

        $this->assertEquals($createdTask->id, $task->id);
    }

    public function test_throws_exception_when_task_not_found()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->taskRepository->find(9999999999999);
    }

    public function test_can_update_a_task()
    {
        $task = Task::factory()->create();
        $data = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => TaskStatus::Completed->value,
        ];
        $updatedTask = $this->taskRepository->update($task->id, $data);

        $this->assertEquals($data['title'], $updatedTask->title);
        $this->assertEquals($data['description'], $updatedTask->description);
        $this->assertEquals($data['status'], $updatedTask->status);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_throws_exception_when_updating_a_task_that_does_not_exist()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->taskRepository->update(9999999999999, []);
    }

    public function test_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $this->taskRepository->delete($task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function tearDown(): void
    {
        // clean all mock after run
        Mockery::close();

        // cleaning unit test
        parent::tearDown();
    }
}
