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

$router->group(['middleware' => 'auth','prefix' => 'api'], function ($router){
    $router->get('me', 'AuthController@me');

    $router->group(['prefix' => 'teachers'], function () use ($router) {
        $router->post('add', 'TeachersController@add');
        $router->get('list', 'TeachersController@list');
        $router->get('detail', 'TeachersController@detail');
        $router->post('update', 'TeachersController@update');
        $router->post('update-status-active', 'TeachersController@edit_status_active');
        $router->post('update-status-verifikasi', 'TeachersController@edit_status_verifikasi');
    });

    $router->group(['prefix' => 'class'], function () use ($router) {
        $router->post('add', 'ClassController@add');
        $router->get('list', 'ClassController@list');
        $router->get('detail', 'ClassController@detail');
        $router->post('update', 'ClassController@update');
        $router->post('update-status', 'ClassController@edit_status_active');
    });

    $router->group(['prefix' => 'section'], function () use ($router) {
        $router->post('add', 'SectionController@add');
        $router->get('list', 'SectionController@list');
        $router->get('detail', 'SectionController@detail');
        $router->post('update', 'SectionController@update');
        $router->post('update-status', 'SectionController@edit_status_active');
    });

    $router->group(['prefix' => 'rooms'], function () use ($router) {
        $router->post('add', 'RoomsController@add');
        $router->get('list', 'RoomsController@list');
        $router->get('detail', 'RoomsController@detail');
        $router->post('update', 'RoomsController@update');
        $router->post('update-status', 'RoomsController@edit_status_active');
    });
});

$router->group(['prefix' => 'api'], function () use ($router) {
   $router->post('register', 'AuthController@register');
   $router->post('login', 'AuthController@login');
   $router->get('list_role', 'RoleController@list_role');
});