<?php

use App\Http\Controllers\ClientController;
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
Route::controller(ClientController::class)->prefix('clients')->name('clients.')->group(function () {
    Route::get('/services/contracts', 'contracts')->name('services.contracts'); // Cada ruta debe ser específica si se combina
    Route::post('/services/attach', 'attach')->name('services.attach');
    Route::post('/services/detach', 'detach')->name('services.detach');
});

// Rutas para trabajo de relaciones con servicios y clientes
Route::controller(ServiceController::class)->prefix('services')->name('services.')->group(function () {
    Route::get('/clients/contracts', 'contracts')->name('clients.contracts');
});
