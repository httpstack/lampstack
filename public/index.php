<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../controllers/classes/Router.php';
require '../controllers/routes/Home.php';
require '../controllers/routes/About.php';
/*
require 'AboutController.php';
require 'UserController.php';
*/
$router = new Router();

// Define routes
$router->addRoute('/', [new Home(), 'index']);
$router->addRoute('/About', [new About(), 'index']);
$router->addRoute('/Services/Development', [new Services("Development"), 'index']);
$router->addRoute('/Projects/:intProjectID', ['Projects', 'message']);


// Get the request URI and remove the "public" part
$request = $_SERVER['REQUEST_URI'];
$request = preg_replace("/^\/public/", "", $request);

// Dispatch the request
$router->dispatch($request);
?>