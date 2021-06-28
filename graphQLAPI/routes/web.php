<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', function () {
    if(Auth::guest()) {
        return redirect('/login');
    }
    return view('home');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/home', function() {
        return view('home');
    });
    Route::get('/update/webshop/{id}', [\App\Http\Controllers\WebshopController::class, 'update'])->name('webshop.edit');
    Route::get('/delete/webshop/{id}', [\App\Http\Controllers\WebshopController::class, 'destroy'])->name('webshop.delete');
    Route::get('/create/product', [\App\Http\Controllers\WebshopController::class, 'add'])->name('webshop.add');
});

//Handlers
Route::post('/handle/update', [\App\Http\Controllers\WebshopController::class, 'edit'])->name('webshop.update');
Route::post('/handle/create', [\App\Http\Controllers\WebshopController::class, 'create'])->name('webshop.create');
