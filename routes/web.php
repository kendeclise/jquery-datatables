<?php

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


Route::group(['prefix'=>'datatables'],function() {
    //Vistas
    Route::get('/main', 'ViewController@mainView')->name('view.datatables.main');

    //Respuestas Datatable
    Route::get('/products-datatable-server-side', 'ProductController@productsDatatableServerSideFormat')->name('datatables.productsDatatableServerSideFormat');

    //Rest adicional
    Route::delete('/products/{id}', 'ProductController@destroy')->name('products.destroy');
});

