<?php

use NatoxCore\Application;
use Natox\controllers\SiteController;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = new Application(dirname(__DIR__));

$app->on(Application::EVENT_BEFORE_REQUEST, function () {
    // echo "Before request from second installation </br>";

});

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->get('/contact/{id}', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);
$app->router->get('/about', [SiteController::class, 'about']);
$app->router->delete('/about/{id}', [SiteController::class, 'about']);

$app->router->get('/login', [SiteController::class, 'login']);

$app->run();