<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientServiceController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para clientes y servicios
Route::apiResources([
    'clients'  => ClientController::class,
    'services' => ServiceController::class,
]);

// Rutas para trabajo de relaciones con clientes y servicios
Route::controller(ClientServiceController::class)->prefix('clients/services')->name('clients.services.')->group(function () {
    Route::get('/contracts/{type?}', 'contracts')->name('contracts');
    Route::post('/attach/{type?}', 'attach')->name('attach');
    Route::post('/detach/{type?}', 'detach')->name('detach');
});
