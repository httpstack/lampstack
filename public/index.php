<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/html/logs/errors.log'); // Specify your desired log file path

require '../src/controllers/classes/_Router.php';
require '../src/controllers/classes/Doc.php';
require '../src/controllers/classes/File.php';
require '../src/controllers/classes/Template.php';
require '../src/controllers/routes/Products.php';
require '../src/controllers/routes/Home.php';

$router = new Router();

// Middleware to prepare the template
$router->use('*', function($request, $router, $next) {
    $template = new Template('../src/templates/public.html');
    $template->addData('title', 'My Page Title');
    $template->addData('content', 'This is the content of the page.');
    $document = $template->render();
    $router->setRenderedDocument($document);
    $next();
});

// Example middleware for specific route
$router->use('/user/*', function($request, $router, $next) {
    // Check authentication
    if (!isset($_SESSION['user'])) {
        Router::redirect('/login');
    }
    $next();
});

// Define routes for CRUD operations
$router->get('/', ['Home', 'index']);
$router->get('/Products', ['Products', 'index']);
$router->get('/Products/:id', ['Products', 'show']);
$router->post('/Products', ['Products', 'create']);
$router->put('/Products/:id', ['Products', 'update']);
$router->delete('/Products/:id', ['Products', 'delete']);

// Dispatch the request
$router->dispatch();
?>