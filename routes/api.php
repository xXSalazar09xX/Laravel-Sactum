<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TipoUsuarioController;
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

Route::group(['middleware' => ["auth:sanctum"]], function () {

Route::delete('usuario/{id}',[AuthController::class, 'destroy']);
Route::put('usuario/{id}',[AuthController::class, 'update']);
Route::get('usuario/search/{name}',[AuthController::class, 'ShowUser']);
Route::resource("tipo",TipoUsuarioController::class); 
Route::get('tipos/usuario',[TipoUsuarioController::class, 'listTipoUsuario']);


    
});
//crear usuario
Route::get('usuario',[AuthController::class, 'index']);
Route::post('registrarse', [AuthController::class, 'store']);
Route::post('logeo',[AuthController::class, 'logear']);
