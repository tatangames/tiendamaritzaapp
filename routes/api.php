<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Productos\ApiProductosController;
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

Route::post('usuario/buscar/producto', [ApiProductosController::class, 'buscarProducto']);


Route::post('usuario/registrar/producto', [ApiProductosController::class, 'registrarProducto']);


Route::post('usuario/actualizar/producto/precio', [ApiProductosController::class, 'actualizarProductoPrecio']);


Route::post('usuario/historial/precios', [ApiProductosController::class, 'historialPreciosProducto']);

// informacion de un producto
Route::post('usuario/informacion/producto', [ApiProductosController::class, 'informacionProducto']);





