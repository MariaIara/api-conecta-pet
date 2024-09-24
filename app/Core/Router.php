<?php

namespace App\Core;

class Router
{
    private static $routes = [];

    public static function get(string $path, callable|string $handler): void
    {
        self::addRoute('GET', $path, $handler);
    }

    public static function post(string $path, callable|string $handler): void
    {
        self::addRoute('POST', $path, $handler);
    }

    public static function put(string $path, callable|string $handler): void
    {
        self::addRoute('PUT', $path, $handler);
    }

    public static function delete(string $path, callable|string $handler): void
    {
        self::addRoute('DELETE', $path, $handler);
    }

    private static function addRoute(string $method, string $path, callable|string $handler): void
    {
        $path = preg_replace('/{([^}]+)}/', '(?P<$1>[^/]+)', $path);
        self::$routes[] = compact('method', 'path', 'handler');
    }

    public static function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match('#^' . $route['path'] . '$#', $path, $matches)) {
                $handler = $route['handler'];

                if (is_string($handler)) {
                    list($controller, $action) = explode('::', $handler);
                    $controllerClass = 'App\\Controllers\\' . $controller;

                    if (class_exists($controllerClass)) {
                        $controllerInstance = new $controllerClass();
                        if (method_exists($controllerInstance, $action)) {
                            return call_user_func_array([$controllerInstance, $action], array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
                        } else {
                            http_response_code(500);
                            throw new \Exception("Method $action not found in $controllerClass");
                        }
                    } else {
                        http_response_code(500);
                        throw new \Exception("Controller $controllerClass not found");
                    }
                } elseif (is_callable($handler)) {
                    return call_user_func_array($handler, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
                }
            }
        }

        return view('not_found');
    }
}
