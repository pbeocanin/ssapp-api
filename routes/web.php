<?php

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
    return false;
});


$router->post('/register', 'UsersController@register');
$router->post('/login', 'AuthController@authenticate');
$router->get('/screenshot/{id}', 'ScreenshotsController@getSS');


$router->group(['middleware' => 'jwt.auth'],
    function() use ($router) {
        $router->put('/screenshot', 'ScreenshotsController@prepSS');
        $router->post('/screenshot', 'ScreenshotsController@saveSS');
        $router->patch('/screenshot/{id}', 'ScreenshotsController@setExp');
    });