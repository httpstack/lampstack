<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../src/controllers/classes/Router.php';
require '../src/controllers/classes/Doc.php';
require '../src/controllers/classes/File.php';
require '../src/controllers/classes/Template.php';
require '../src/controllers/routes/Home.php';
require '../src/controllers/routes/About.php';
require '../src/controllers/routes/Services.php';
require '../src/controllers/routes/User.php';

$router = new Router();

// Middleware to prepare the template
$router->use('*', function($request, $router) {
    $template = new Template('../views/template.php');
    $template->addData('title', 'My Page Title');
    $template->addData('content', 'This is the content of the page.');
    $document = $template->render();
    $router->setRenderedDocument($document);
});

// Example middleware for specific route
$router->use('/user/*', function($request, $router) {
    // Check authentication
    if (!isset($_SESSION['user'])) {
        Router::redirect('/login');
    }
});

// Define routes
$router->addRoute('/', ['Home', 'index']);
$router->addRoute('/About', ['About', 'index']);
$router->addRoute('/Services/Development', ['Services', 'index', '/Development']);
$router->addRoute('/Services', ['Services', 'index']);
$router->addRoute('/user/:intUserID/dash/inbox/message/:intMessageID', ['User', 'index', '/dash/inbox/message']);

// Get the request URI and remove the "public" part
$request = $_SERVER['REQUEST_URI'];
$request = preg_replace("/^\/public/", "", $request);

// Dispatch the request
$router->dispatch($request);
?>