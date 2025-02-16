<?php

class Router {
    private $routes = [];

    public function addRoute($pattern, $callback) {
        $this->routes[$pattern] = $callback;
    }

    public function dispatch($request) {
        foreach ($this->routes as $pattern => $callback) {
            $pattern = preg_replace('/:\w+/', '(\d+)', $pattern); // Match digits for parameters
            if (preg_match("#^$pattern$#", $request, $matches)) {
                array_shift($matches); // Remove the full match
                if (is_array($callback) && is_string($callback[0])) {
                    $controller = new $callback[0](...$matches);
                    return call_user_func([$controller, $callback[1]]);
                } else {
                    return call_user_func($callback, $matches);
                }
            }
        }

        // Default 404 response
        http_response_code(404);
        echo "404 - Page not found.";
    }
}
?>