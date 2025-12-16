<?php

namespace Tests\Unit\Services\Tasks;

use App\Enums\TaskStatus;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\Contracts\TaskServiceInterface;
use App\Services\Tasks\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskServiceInterface $service;
    protected $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(TaskRepositoryInterface::class);

        $this->service = new TaskService($this->repositoryMock);
    }

    public function test_can_get_all_tasks()
    {
        $collection = Mockery::mock(AnonymousResourceCollection::class);

        $this->repositoryMock->shouldReceive('getAll')->once()->andReturn($collection);

        $result = $this->service->getAll();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $result);
    }

    public function test_create_task()
    {

        $taskData = [
            'title' => 'Task Title',
            'description' => 'Task Description',
            'status' => TaskStatus::Pending->value,
        ];
        $createdTask = Task::factory()->make($taskData);


        $this->repositoryMock->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn($createdTask);

        $result = $this->service->create($taskData);

        $this->assertInstanceOf(TaskResource::class, $result);

        $this->assertEquals($taskData['title'], $result->title);
        $this->assertEquals($taskData['description'], $result->description);
        $this->assertEquals($taskData['status'], $result->status);
    }

    public function test_find_a_task()
    {
        $task = Task::factory()->create();

        $this->repositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($task->id)
            ->andReturn($task);

        $result = $this->service->find($task->id);

        $this->assertInstanceOf(TaskResource::class, $result);
        $this->assertEquals($task->id, $result->id);
        $this->assertEquals($task->title, $result->title);
        $this->assertEquals($task->description, $result->description);
        $this->assertEquals($task->status, $result->status);
    }

    public function test_update_a_task()
    {
        $task = Task::factory()->create();
        $taskData = [
            'title' => 'New Task Title',
            'description' => 'New Task Description',
            'status' => TaskStatus::Pending->value,
        ];
        $updatedTask = Task::query()->find($task->id);
        $updatedTask->update($taskData);


        $this->repositoryMock->shouldReceive('update')->once()->with($task->id, $taskData)->andReturn($updatedTask);

        $result = $this->service->update($task->id, $taskData);

        $this->assertInstanceOf(TaskResource::class, $result);
        $this->assertEquals($taskData['title'], $result->title);
        $this->assertEquals($taskData['description'], $result->description);
        $this->assertEquals($taskData['status'], $result->status);
    }

    public function test_delete_a_task()
    {
        $this->repositoryMock->shouldReceive('delete')->once()->with(1)->andReturn(true);

        $this->service->delete(1);
        $this->assertTrue(true);
    }

    public function tearDown(): void
    {
        // clean all mock after run
        Mockery::close();

        // cleaning unit test
        parent::tearDown();
    }
}
