<?php
$router->post('/api/auth/register', 'AuthController@apiRegister');
$router->post('/api/auth/login', 'AuthController@apiLogin');
$router->post('/api/auth/logout', 'AuthController@apiLogout');
$router->get('/api/me', 'MeController@me');
$router->put('/api/me', 'MeController@update');

$router->get('/api/companies', 'CompaniesController@index');
$router->post('/api/companies', 'CompaniesController@store');
$router->put('/api/companies/:id', 'CompaniesController@update');

$router->get('/api/companies/:companyId/warehouses', 'WarehousesController@index');
$router->post('/api/companies/:companyId/warehouses', 'WarehousesController@store');
$router->put('/api/warehouses/:id', 'WarehousesController@update');
$router->delete('/api/warehouses/:id', 'WarehousesController@delete');

$router->get('/api/companies/:companyId/categories', 'CategoriesController@index');
$router->post('/api/companies/:companyId/categories', 'CategoriesController@store');
$router->put('/api/categories/:id', 'CategoriesController@update');
$router->delete('/api/categories/:id', 'CategoriesController@delete');

$router->get('/api/warehouses/:warehouseId/products', 'ProductsController@index');
$router->post('/api/warehouses/:warehouseId/products', 'ProductsController@store');
$router->put('/api/products/:id', 'ProductsController@update');
$router->get('/api/products/search', 'ProductsController@search');

$router->get('/api/warehouses/:warehouseId/income', 'IncomeController@list');
$router->post('/api/warehouses/:warehouseId/income', 'IncomeController@store');

$router->get('/api/warehouses/:warehouseId/orders', 'OrdersController@list');
$router->post('/api/warehouses/:warehouseId/orders', 'OrdersController@store');
$router->put('/api/orders/:id/status', 'OrdersController@updateStatus');

$router->get('/api/companies/:companyId/services', 'ServicesController@index');
$router->post('/api/companies/:companyId/services', 'ServicesController@store');
$router->put('/api/services/:id', 'ServicesController@update');
$router->delete('/api/services/:id', 'ServicesController@delete');

$router->get('/api/dashboard', 'DashboardController@stats');
