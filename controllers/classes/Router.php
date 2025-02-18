<?php

class Router {
    private $routes = [];
    private $middleware = [];

    public function addRoute($pattern, $callback) {
        $this->routes[$pattern] = $callback;
    }

    public function use($pattern, $middleware = null) {
        if ($middleware === null) {
            $middleware = $pattern;
            $pattern = '*';
        }
        $this->middleware[] = ['pattern' => $pattern, 'middleware' => $middleware];
    }
    static public function redirect($url) {
        header("Location: $url");
        exit();
    }
    public function dispatch($request) {
        foreach ($this->middleware as $mw) {
            if ($this->matchPattern($mw['pattern'], $request)) {
                call_user_func($mw['middleware'], $request);
            }
        }

        foreach ($this->routes as $pattern => $callback) {
            $patternRegex = preg_replace('/:\w+/', '(\d+)', $pattern); // Match digits for parameters
            if (preg_match("#^$patternRegex$#", $request, $matches)) {
                array_shift($matches); // Remove the full match
                $varControllerClass = $callback[0];
                $strControllerMethod = $callback[1];
                $strSubRoutes = isset($callback[2]) ? $callback[2] : '';

                if (is_string($callback[0])) {
                    // Extract URL variables
                    $urlVars = $this->extractUrlVars($pattern, $request);
                    // Create the controller instance with the extracted variables
                    $objRouteController = new $varControllerClass($strSubRoutes, $urlVars);
                    return call_user_func([$objRouteController, $strControllerMethod]);
                } else {
                    return call_user_func($callback, $matches);
                }
            }
        }

        // Default 404 response
        http_response_code(404);
        echo "404 - Page not found.";
    }

    private function extractUrlVars($pattern, $request) {
        $patternParts = explode('/', trim($pattern, '/'));
        $requestParts = explode('/', trim($request, '/'));
        $urlVars = [];

        foreach ($patternParts as $index => $part) {
            if (strpos($part, ':') === 0) {
                $varName = substr($part, 1);
                $urlVars[$varName] = $requestParts[$index];
            }
        }

        return $urlVars;
    }

    private function matchPattern($pattern, $request) {
        $pattern = str_replace('*', '.*', $pattern);
        return preg_match("#^$pattern$#", $request);
    }
}
?>