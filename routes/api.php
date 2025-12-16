<?php

use App\Http\Controllers\Api\TaskController;

Route::as("api.")->group(function () {
    Route::apiResource('tasks', TaskController::class);
});
