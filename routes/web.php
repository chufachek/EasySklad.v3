<?php
$router->get('/', function () {
    if (Auth::check()) {
        Response::redirect('/app/dashboard');
    }
    Response::redirect('/login');
});

$router->get('/login', 'AuthController@showLogin');
$router->get('/register', 'AuthController@showRegister');
$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

$router->get('/__rewrite_probe', function () {
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Rewrite OK';
});

$router->get('/__health', function () {
    $mode = Request::routingMode();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('status' => 'OK', 'routing' => $mode));
});

$router->get('/app', function () {
    AuthMiddleware::ensure();
    Response::redirect('/app/dashboard');
});

$router->get('/app/dashboard', 'DashboardController@page');
$router->get('/app/profile', 'MeController@profile');
$router->get('/app/company', 'CompaniesController@company');
$router->get('/app/warehouses', 'WarehousesController@page');
$router->get('/app/products', 'ProductsController@page');
$router->get('/app/categories', 'CategoriesController@page');
$router->get('/app/income', 'IncomeController@page');
$router->get('/app/orders', 'OrdersController@page');
$router->get('/app/services', 'ServicesController@page');

$router->setNotFound(function () {
    header('HTTP/1.0 404 Not Found');
    Helpers::render('landing');
});
