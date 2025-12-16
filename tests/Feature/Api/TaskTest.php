<?php

namespace Tests\Feature\Api;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson(route('api.tasks.index'));

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    public function test_it_can_create_a_task(): void
    {
        $payload = [
            'title' => 'Task Title',
            'description' => 'Task Description',
            'status' => TaskStatus::Pending->value,
        ];

        $response = $this->postJson(route('api.tasks.store'), $payload);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas((new Task())->getTable(), [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'status' => $payload['status'],
        ]);
    }

    public function test_it_can_show_single_task(): void
    {
        $task = Task::factory()->create();
        $response = $this->getJson(route('api.tasks.show', $task));
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
            ]);
    }

    public function test_it_can_update_a_task(): void
    {
        $task = Task::factory()->create();
        $payload = [
            'title' => 'Task Title',
            'description' => 'Task Description',
            'status' => TaskStatus::Pending->value,
        ];

        $response = $this->putJson(route('api.tasks.update', $task), $payload);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'id' => $task->id,
                'title' => $payload['title'],
                'description' => $payload['description'],
                'status' => $payload['status'],
                'created_at' => $task->created_at,
            ]);
        $this->assertDatabaseHas((new Task())->getTable(), [
            'id' => $task->id,
            'title' => $payload['title'],
            'description' => $payload['description'],
            'status' => $payload['status'],
        ]);
    }

    public function test_it_can_delete_a_task(): void
    {
        $task = Task::factory()->create();
        $response = $this->deleteJson(route('api.tasks.destroy', $task));
        $response
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing((new Task())->getTable(), [
            'id' => $task->id,
        ]);
    }

    public function test_validate_required_fields_when_creating_a_task(): void
    {
        $payload = [];

        $response = $this->postJson(route('api.tasks.store'), $payload);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'title'
            ]);
    }

    public function test_validates_invalid_status()
    {
        $task = Task::factory()->create();

        $response = $this->putJson(route('api.tasks.update', $task->id), [
            'status' => 'invalid_status'
        ]);


        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['status'])
            ->assertJsonStructure([
                'message',
                "errors" => [
                    'status',
                ],
            ]);
    }
}
