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
Route::group(['middleware' => ['guest']], function () {
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {


    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Almacenero']], function () {
        Route::get('/category','CategoryController@index');
        Route::post('/category/register','CategoryController@store');
        Route::put('/category/update','CategoryController@update');
        Route::put('/category/deactivate','CategoryController@deactivate');
        Route::put('/category/activate','CategoryController@activate');
        Route::get('/category/selectCategory','CategoryController@selectCategory');

        Route::get('/article','ArticleController@index');
        Route::post('/article/register','ArticleController@store');
        Route::put('/article/update','ArticleController@update');
        Route::put('/article/deactivate','ArticleController@deactivate');
        Route::put('/article/activate','ArticleController@activate');
        Route::get('/article/buscarArticulo','ArticleController@buscarArticulo');
        Route::get('/article/listarArticulo','ArticleController@listarArticulo');

        Route::get('/supplier','SupplierController@index');
        Route::post('/supplier/register','SupplierController@store');
        Route::put('/supplier/update','SupplierController@update');
        Route::get('/supplier/selectSupplier','SupplierController@selectSupplier');

        Route::get('/ingreso','IngresoController@index');
        Route::post('/ingreso/register','IngresoController@store');
        Route::put('/ingreso/deactivate','IngresoController@deactivate');
        Route::get('/ingreso/obtenerCabecera','IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles','IngresoController@obtenerDetalles');
    });

        Route::group(['middleware' => ['Vendedor']], function () {
        Route::get('/client','ClienteController@index');
        Route::post('/client/register','ClienteController@store');
        Route::put('/client/update','ClienteController@update');
    });

    Route::group(['middleware' => ['Administrador']], function () {

        Route::get('/category','CategoryController@index');
        Route::post('/category/register','CategoryController@store');
        Route::put('/category/update','CategoryController@update');
        Route::put('/category/deactivate','CategoryController@deactivate');
        Route::put('/category/activate','CategoryController@activate');
        Route::get('/category/selectCategory','CategoryController@selectCategory');

        Route::get('/article','ArticleController@index');
        Route::post('/article/register','ArticleController@store');
        Route::put('/article/update','ArticleController@update');
        Route::put('/article/deactivate','ArticleController@deactivate');
        Route::put('/article/activate','ArticleController@activate');
        Route::get('/article/buscarArticulo','ArticleController@buscarArticulo');
        Route::get('/article/listarArticulo','ArticleController@listarArticulo');

        Route::get('/supplier','SupplierController@index');
        Route::post('/supplier/register','SupplierController@store');
        Route::put('/supplier/update','SupplierController@update');
        Route::get('/supplier/selectSupplier','SupplierController@selectSupplier');

        Route::get('/ingreso','IngresoController@index');
        Route::post('/ingreso/register','IngresoController@store');
        Route::put('/ingreso/deactivate','IngresoController@deactivate');
        Route::get('/ingreso/obtenerCabecera','IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles','IngresoController@obtenerDetalles');

        Route::get('/client','ClienteController@index');
        Route::post('/client/register','ClienteController@store');
        Route::put('/client/update','ClienteController@update');

        Route::get('/rol','RolController@index');
        Route::get('/rol/selectRol','RolController@selectRol');
        
        Route::get('/user','UserController@index');
        Route::post('/user/register','UserController@store');
        Route::put('/user/update','UserController@update');
        Route::put('/user/deactivate','UserController@deactivate');
        Route::put('/user/activate','UserController@activate');
    });    
        
});



//Route::get('/home', 'HomeController@index')->name('home');
