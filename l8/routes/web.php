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

Route::middleware(['auth:sanctum', 'verified'])->get('/about', function () {
    return view('about');
})->name('about');

Route::middleware(['auth:sanctum', 'verified'])->get('/services', function () {
    return view('services');
})->name('services');

Route::middleware(['auth:sanctum', 'verified'])->get('/contact', function () {
    return view('contact');
})->name('contact');

Route::middleware(['auth:sanctum', 'verified'])->get('/', 'HomeController@showAll')
    ->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/categories/restore', 'CategoryController@showRestore')->name('showCategoryRestore');
Route::middleware(['auth:sanctum', 'verified'])->get('/categories/{id}/restore', 'CategoryController@restore')->name('categoryRestore');
Route::middleware(['auth:sanctum', 'verified'])->delete('/categories/{id}/force-destroy', 'CategoryController@forceDestroy')->name('categories.forceDestroy');


Route::middleware(['auth:sanctum', 'verified'])->get('/products/restore', 'ProductController@showRestore')->name('showProductRestore');
Route::middleware(['auth:sanctum', 'verified'])->get('/products/{id}/restore', 'ProductController@restore')->name('productRestore');
Route::middleware(['auth:sanctum', 'verified'])->delete('/products/{id}/force-destroy', 'ProductController@forceDestroy')->name('products.forceDestroy');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['middleware' => 'auth'], function (){
    Route::resource('categories', 'CategoryController');
    Route::resource('products', 'ProductController')->except(['show']);
});

Route::group(['prefix' => 'digging_deeper',], function (){
    Route::get('collections', 'DiggingDeeperController@collections')->name('digging_deeper');
});
