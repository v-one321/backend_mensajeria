<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CajaController;
use App\Http\Controllers\api\CategoriasController;
use App\Http\Controllers\api\ChatController;
use App\Http\Controllers\api\PedidoController;
use App\Http\Controllers\api\ProductosController;
use App\Http\Controllers\api\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(["middleware" => "auth:sanctum"], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    //          CLIENTE
    Route::get('/usuario', [UsuarioController::class,'index']);
    Route::get('/chatClientView/{id}', [ChatController::class, 'viewClientChat']);
    Route::post('/chatClient', [ChatController::class, 'storeClientChat']);
    Route::get('/chatUltimos', [ChatController::class, 'chatRecientes']);
    /****************************       Productos ***************** */
    Route::get('/productos', [ProductosController::class, 'index']);
    Route::get('/productos-aleatorio', [ProductosController::class, 'productoAleatorio']);
    /****************************       Categorias ***************** */
    Route::get('/categorias', [CategoriasController::class, 'index']);
    /*************************          PEDIDOS         ******************************** */
    Route::post('/pedido', [PedidoController::class, 'store']);
    Route::get('/pedido', [PedidoController::class, 'index']);
    /****************************       CAJA            ********************************* */
    Route::get('/caja', [CajaController::class, 'index']);
    Route::post('/caja', [CajaController::class, 'store']);
    Route::get('/caja/{id}', [CajaController::class, 'show']);
    Route::put('/caja/{id}', [CajaController::class, 'update']);
    Route::delete('/caja/{id}', [CajaController::class, 'destroy']);
    Route::get('/persona', [CajaController::class, 'searchPersona']);
    Route::get('/persona/{id}', [CajaController::class, 'selectPersona']);
    Route::get('/caja-flujo', [CajaController::class, 'flujoCaja']);
});

Route::post('/login', [AuthController::class, 'login']);
/***************        CREAR USUARIO       ****************************** */
Route::post('/usuario', [UsuarioController::class, 'store']);
/***************        CREAR CLIENTE       ****************************** */
Route::post('/usuario-cliente', [UsuarioController::class, 'storeCliente']);