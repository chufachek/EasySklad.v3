<?php
$config = include __DIR__ . '/../config/config.php';

if (!empty($config['app']['session_name'])) {
    session_name($config['app']['session_name']);
}
$cookieParams = session_get_cookie_params();
session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], true);
session_start();

$forceHttps = $config['app']['force_https'];
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
if ($forceHttps && !$isHttps) {
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    header('Location: https://' . $host . $uri, true, 301);
    exit;
}

spl_autoload_register(function ($class) {
    $paths = array(
        __DIR__ . '/../src/Core/' . $class . '.php',
        __DIR__ . '/../src/Controllers/' . $class . '.php',
        __DIR__ . '/../src/Models/' . $class . '.php',
        __DIR__ . '/../src/Services/' . $class . '.php',
        __DIR__ . '/../src/Middleware/' . $class . '.php'
    );
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$router = new Router();

require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/api.php';

$method = Request::method();
$path = Request::path();

$router->dispatch($method, $path);
