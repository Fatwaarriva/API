<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1/user', 'middleware'=>'auth'], function() use ($router){
	$router->get('/', ['uses' => 'UserController@index']);
});

$router->group(['prefix' => 'api/v1/costumers', 'middleware'=>'auth'], function() use ($router){
	$router->get('/', ['uses' => 'CostumerController@index']); 
	$router->post('/', ['uses' => 'CostumerController@store']);
    $router->get('/{id}', ['uses' => 'CostumerController@show']);
    $router->put('/{id}', ['uses' => 'CostumerController@edit']);
    $router->delete('/{id}', ['uses' => 'CostumerController@destroy']);

});
$router->group(['prefix' => 'api/v1/products', 'middleware'=>'auth'], function() use ($router){
		$router->get('/', ['uses' => 'ProductController@index']);
		$router->post('/', ['uses' => 'ProductController@store']);
    	$router->get('/{id}', ['uses' => 'ProductController@show']);
    	$router->put('/{id}', ['uses' => 'ProductController@edit']);
    	$router->delete('/{id}', ['uses' => 'ProductController@destroy']);
});

$router->group(['prefix' => 'api/v1/order','middleware' => 'auth'], function() use ($router){
    $router->get('/', ['uses' => 'OrderController@index']);
	$router->post('/', ['uses' => 'OrderController@store']);
    $router->get('/{id}', ['uses' => 'OrderController@show']);
    $router->put('/{id}', ['uses' => 'OrderController@edit']);
    $router->delete('/{id}', ['uses' => 'OrderController@destroy']);
});

$router->group(['prefix' => 'api/v1/orderitems','middleware' => 'auth'], function() use ($router){
    $router->get('/', ['uses' => 'OrderitemController@index']);
	$router->post('/', ['uses' => 'OrderitemController@add']);
    $router->get('/{id}', ['uses' => 'OrderitemController@show']);
    $router->put('/{id}', ['uses' => 'OrderitemController@update']);
    $router->delete('/{id}', ['uses' => 'OrderitemController@delete']);
});

$router->group(['prefix' => 'api/v1/orderitems-join','middleware' => 'auth'], function() use ($router){
    $router->get('/', ['uses' => 'OrderitemController@showDataJoin']);
	$router->get('/{id}', ['uses' => 'OrderitemController@showidJoin']);


});

$router->group(['prefix' => 'api/v1/payment', 'middleware' => 'auth'], function() use ($router) {
    $router->get('/', ['uses' => 'PaymentController@index']);
    $router->post('/', ['uses' => 'PaymentController@store']);
    $router->get('/{id}', ['uses' => 'PaymentController@show']);
    $router->put('/{id}', ['uses' => 'PaymentController@edit']);
    $router->delete('/{id}', ['uses' => 'PaymentController@destroy']);
});

//$router->group(['prefix' => 'api/v1/testing','middleware'=>'auth'], function() use ($router){
	
	
	//$router->get('/transaction', ['uses' => 'TransactionController@index']);
	//$router->post('/transaction', ['uses' => 'TransactionController@store']);
	//$router->put('/transaction/{id}', ['uses' => 'TransactionController@update']);
	//$router->get('/transaction/{id}', ['uses' => 'TransactionController@show']);
	
	//$router->get('/list-transaction-by-product/{id}', ['uses' => 'ProductController@listTransaction']);
	
	//$router->get('/', ['uses' => 'ProductController@index']);
	//$router->post('/', ['uses' => 'ProductController@store']);
	//$router->get('/{id}', ['uses' => 'ProductController@show']);
	//$router->delete('/{id}', ['uses' => 'ProductController@destroy']);
	//$router->put('/{id}', ['uses' => 'ProductController@update']);

//});