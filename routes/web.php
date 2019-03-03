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
    Route::get('/basic-backend', 'ViewController@datatableBasicView')->name('view.datatables.basicbackend');
    Route::get('/server-side', 'ViewController@dataTableServerSideView')->name('view.datatables.serverside');

    //Respuestas Datatable
    Route::get('/products-datatable-server-side', 'ProductController@productsDatatableServerSideFormat')->name('datatables.productsDatatableServerSideFormat');
    Route::get('/categories-basic-datatable', 'CategoryController@categoriesBasicDatatableFormat')->name('datatables.categoriesBasicDatatableFormat');

    //Rest adicional
    Route::delete('/products/{id}', 'ProductController@destroy')->name('products.destroy');
    Route::delete('/categories/{id}', 'CategoryController@destroy')->name('category.destroy');
});

