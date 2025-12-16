<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Services\Contracts\TaskServiceInterface;
use App\Services\Tasks\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function __construct(private readonly TaskServiceInterface $taskService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = $this->taskService->getAll();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json([
                'message' => 'Something went wrong on our server. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = $this->taskService->create($request->validated());
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [
                'payload' => $request->validated(),
            ]);
            return response()->json([
                'message' => 'Something went wrong on our server. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $task = $this->taskService->find($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [
                'id' => $id,
            ]);
            return response()->json([
                'message' => 'Something went wrong on our server. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        try {
            $task = $this->taskService->update($id, $request->validated());
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [
                'id' => $id,
                'payload' => $request->validated(),
            ]);
            return response()->json([
                'message' => 'Something went wrong on our server. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->taskService->delete($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [
                'id' => $id,
            ]);

            return response()->json([
                'message' => 'Something went wrong on our server. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
