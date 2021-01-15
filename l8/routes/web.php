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

Route::middleware(['middleware' => 'auth'])->get('/about', function () {
    return view('about');
})->name('about');

Route::middleware(['middleware' => 'auth'])->get('/services', function () {
    return view('services');
})->name('services');

Route::middleware(['middleware' => 'auth'])->get('/contact', function () {
    return view('contact');
})->name('contact');

Route::middleware(['middleware' => 'auth'])->get('/', 'HomeController@showAll')
    ->name('home');

Route::middleware(['middleware' => 'auth'])->get('/categories/restore', 'CategoryController@showRestore')->name('showCategoryRestore');
Route::middleware(['middleware' => 'auth'])->get('/categories/{id}/restore', 'CategoryController@restore')->name('categoryRestore');
Route::middleware(['middleware' => 'auth'])->delete('/categories/{id}/force-destroy', 'CategoryController@forceDestroy')->name('categories.forceDestroy');


Route::middleware(['middleware' => 'auth'])->get('/products/restore', 'ProductController@showRestore')->name('showProductRestore');
Route::middleware(['middleware' => 'auth'])->get('/products/{id}/restore', 'ProductController@restore')->name('productRestore');
Route::middleware(['middleware' => 'auth'])->delete('/products/{id}/force-destroy', 'ProductController@forceDestroy')->name('products.forceDestroy');

Route::middleware(['middleware' => 'auth'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['middleware' => 'auth'], function (){
    Route::resource('categories', 'CategoryController');
    Route::resource('products', 'ProductController')->except(['show']);
});

Route::group(['prefix' => 'digging_deeper',], function (){
    Route::get('collections', 'DiggingDeeperController@collections')->name('digging_deeper');
});
