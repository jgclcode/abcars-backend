<?php

use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\WebhooksController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get', [App\Http\Controllers\SheetdbController::class, 'get']);
Route::get('pagos/{vehicleId}/{clientId}/{reference?}', [App\Http\Controllers\PaymentController::class, 'pagos'])->name('pagos');

Route::post('webhooks', [App\Http\Controllers\WebhooksController::class, 'webhook']);
//check_view/XXXXXXXXXXXXXXXX1 requiere el vin del vehiculo trae toda la informacion del vehiculo cliente y valuador tecnico
Route::get('/check_view/{vin}', [App\Http\Controllers\Check_ListController::class, 'check_view']); 

Route::get('/authorization', [App\Http\Controllers\ValuationController::class, 'authorization']);

Route::get('/getpolicie', [App\Http\Controllers\PolicieController::class, 'getpolicie']);

Route::get('/downloadFolder/{vin}', [App\Http\Controllers\Ckeck_vehicleController::class, 'downloadFolder']);
