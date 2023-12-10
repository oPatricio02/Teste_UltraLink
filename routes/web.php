<?php


/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\UsuarioController;

// Rotas para TransacaoController
$router->post('/deposito', 'TransacaoController@deposito');
$router->post('/transferencia', 'TransacaoController@transferencia');

// Rotas para UserController
$router->post('/registrar', 'UsuarioController@registrar');
$router->post('/autenticar', 'UsuarioController@autenticar');
$router->get('/dados-usuario', 'UsuarioController@obterDados');




$router->get('/', function () use ($router) {
    return $router->app->version();
});
