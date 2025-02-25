<?php

class Router {
    private $routes = [];
    private $middleware = [];
    private $renderedDocument;

    public function addRoute($strMethod, $pattern, $callback) {
        $this->routes[$pattern] = [$strMethod, $callback];
    }

    public function use($pattern, $middleware = null) {
        if ($middleware === null) {
            $middleware = $pattern;
            $pattern = '*';
        }
        $this->middleware[] = ['pattern' => $pattern, 'middleware' => $middleware];
    }

    public function get($pattern, $callback) {
        $this->addRoute("GET", $pattern, $callback);
    }

    public function post($pattern, $callback) {
        $this->addRoute("POST", $pattern, $callback);
    }

    public function put($pattern, $callback) {
        $this->addRoute("PUT", $pattern, $callback);
    }

    public function delete($pattern, $callback) {
        $this->addRoute("DELETE", $pattern, $callback);
    }

    static public function redirect($url) {
        header("Location: $url");
        exit();
    }

    public function dispatch($request) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->middleware as $mw) {
            if ($this->matchPattern($mw['pattern'], $request)) {
                call_user_func($mw['middleware'], $request, $this);
            }
        }

        foreach ($this->routes as $pattern => [$strMethod, $callback]) {
            if ($strMethod !== $requestMethod) {
                continue;
            }

            $patternRegex = preg_replace('/:\w+/', '(\d+)', $pattern); // Match digits for parameters
            if (preg_match("#^$patternRegex$#", $request, $matches)) {
                array_shift($matches); // Remove the full match
                $varControllerClass = $callback[0];
                $strControllerMethod = $callback[1];
                $strSubRoutes = isset($callback[2]) ? $callback[2] : '';

                 // Extract URL variables
                $urlVars = $this->extractUrlVars($pattern, $request);
                // Create the controller instance with the extracted variables
                $objRouteController = new $varControllerClass($strSubRoutes, $urlVars);
                return call_user_func([$objRouteController, $strControllerMethod], $this->renderedDocument, $strMethod);
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

    public function setRenderedDocument($document) {
        $this->renderedDocument = $document;
    }

    public function getRenderedDocument() {
        return $this->renderedDocument;
    }
}
?>