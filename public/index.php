<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../controllers/classes/Router.php';
require '../controllers/routes/Home.php';
require '../controllers/routes/About.php';
require '../controllers/routes/Services.php';
require '../controllers/routes/User.php';

$router = new Router();
// Example middleware for all routes
$router->use('*', function($request) {
    // Log the request
    error_log("Request: " . $request);
});

// Example middleware for specific route
$router->use('/user/*', function($request) {
    // Check authentication
    if (!isset($_SESSION['user'])) {
        Router::redirect('/login');
    }
});
// Define routes
$router->addRoute('/', ['Home', 'index']);
$router->addRoute('/About', ['About', 'index']);
$router->addRoute('/Services/Development', ['Services', 'index', '/Development']);
$router->addRoute('/user/:intUserID/dash/inbox/message/:intMessageID', ['User', 'index', '/dash/inbox/message']);

// Get the request URI and remove the "public" part
$request = $_SERVER['REQUEST_URI'];
$request = preg_replace("/^\/public/", "", $request);

// Dispatch the request
$router->dispatch($request);
?>