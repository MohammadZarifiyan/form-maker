<?php

use App\Http\Controllers\{
    AuthController,
    ConnectionController,
    FormController,
    ProjectController,
    QueryController,
};

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
auth()->loginUsingId(1);

Route::prefix('account')->middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('connections', ConnectionController::class)->except(['show']);

    Route::apiResource('projects', ProjectController::class)->except(['show']);

    Route::post('queries/{query}/run', [QueryController::class, 'run'])->middleware(['can:run,App\Models\Query']);
    Route::apiResource('queries', QueryController::class);

    Route::get('forms/{id}/check', [FormController::class, 'check']);
    Route::apiResource('forms', FormController::class);
});

Route::prefix('auth')->middleware(['guest:sanctum'])->group(function () {
    Route::get('check', [AuthController::class, 'check'])->withoutMiddleware(['guest:sanctum']);

    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);
});
