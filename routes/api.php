<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VeiculoController;
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

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::apiResources([
        'usuario' => UsuarioController::class,
    ], ['except' => ['store']]); // create com usuário autenticado não foi solicitado.
    Route::apiResources([
        'veiculo' => VeiculoController::class
    ]);
});