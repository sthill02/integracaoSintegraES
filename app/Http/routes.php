<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function(){
	return redirect('login');
});

// login
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::post('logout', 'Auth\AuthController@getLogout');
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');

// web
Route::group(['middleware' => ['auth']], function (){
    Route::get('/', 'SintegraController@index');
    Route::post('/consultar', 'SintegraController@consultarCnpjWeb');
    Route::get('/consultas', 'SintegraController@consultas');
    Route::get('/deletar-consulta/{id}', 'SintegraController@deletarConsulta');
    Route::get('/visualizar-consulta/{id}', 'SintegraController@visualizarConsulta');

});

//api
Route::get('/api/v1/cnpj/{cnpj}', ['middleware' => 'auth.basic', 'uses' => 'SintegraController@apiConsultar']);