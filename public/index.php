<?php

use App\Controllers\DeferTestController;
use App\Controllers\ParallelController;
use App\Controllers\WeatherController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', [WeatherController::class, 'home']);
$app->get('/weather/search', [WeatherController::class, 'showForm']);
$app->post('/weather', [WeatherController::class, 'getWeather']);
$app->get('/defer-test', [DeferTestController::class, 'showTest']);
$app->get('/defer-status', [DeferTestController::class, 'getStatus']);
$app->get('/debug', [ParallelController::class, 'index']);
$app->get('/parallel-test', [ParallelController::class, 'parallelTest']);

$app->run();
