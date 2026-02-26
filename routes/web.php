<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[AuthController::class,'index']);
Route::post('/',[AuthController::class,'authLogin'])->name('auth.login');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');
Route::middleware('auth')->group(function(){

    // AuthController
    Route::get('/dashboard',[AuthController::class,'dashboard'])->name('dashboard');
    Route::get('/product/create',[ProductController::class,'create'])->name('product.create');
    Route::get('/product',[ProductController::class,'product'])->name('product');

    // UserController
    Route::resource('users',UserController::class);

    //RollController
    Route::resource('roles',RoleController::class);
    Route::resource('permissions',PermissionController::class);
});
