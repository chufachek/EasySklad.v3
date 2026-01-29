<?php
class Router
{
    private $routes = array();
    private $notFound;

    public function get($pattern, $handler)
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post($pattern, $handler)
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    public function put($pattern, $handler)
    {
        $this->addRoute('PUT', $pattern, $handler);
    }

    public function delete($pattern, $handler)
    {
        $this->addRoute('DELETE', $pattern, $handler);
    }

    public function setNotFound($handler)
    {
        $this->notFound = $handler;
    }

    private function addRoute($method, $pattern, $handler)
    {
        $this->routes[] = array(
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'regex' => $this->patternToRegex($pattern)
        );
    }

    private function patternToRegex($pattern)
    {
        $regex = preg_replace('#:([a-zA-Z0-9_]+)#', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        return $regex;
    }

    public function dispatch($method, $path)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['regex'], $path, $matches)) {
                $params = array();
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }
                return $this->invokeHandler($route['handler'], $params);
            }
        }

        if ($this->notFound) {
            return $this->invokeHandler($this->notFound, array());
        }

        header('HTTP/1.0 404 Not Found');
        echo 'Not Found';
        return null;
    }

    private function invokeHandler($handler, $params)
    {
        if (is_callable($handler)) {
            return call_user_func($handler, $params);
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($class, $method) = explode('@', $handler, 2);
            if (!class_exists($class)) {
                throw new Exception('Handler class not found: ' . $class);
            }
            $instance = new $class();
            return call_user_func(array($instance, $method), $params);
        }

        throw new Exception('Invalid handler');
    }
}
