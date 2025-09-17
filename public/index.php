<?php

use App\Controllers\WeatherController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', [WeatherController::class, 'home']);
$app->get('/weather/search', [WeatherController::class, 'showForm']);
$app->post('/weather', [WeatherController::class, 'getWeather']);

$app->run();
