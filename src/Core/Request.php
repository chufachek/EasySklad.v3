<?php
class Request
{
    private static $routingMode = 'clean';

    public static function method()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        return $method;
    }

    public static function path()
    {
        if (isset($_GET['page']) && $_GET['page'] !== '') {
            self::$routingMode = 'fallback';
            $path = '/' . trim($_GET['page'], '/');
            return $path === '/' ? '/' : $path;
        }

        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $path = parse_url($uri, PHP_URL_PATH);
        if ($path === null || $path === '') {
            $path = '/';
        }
        return $path;
    }

    public static function input()
    {
        $data = array_merge($_GET, $_POST);
        if (self::method() !== 'GET') {
            $raw = file_get_contents('php://input');
            if ($raw) {
                $json = json_decode($raw, true);
                if (is_array($json)) {
                    $data = array_merge($data, $json);
                }
            }
        }
        return $data;
    }

    public static function routingMode()
    {
        return self::$routingMode;
    }
}
