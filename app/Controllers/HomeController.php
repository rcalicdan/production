<?php

namespace App\Controllers;

use Rcalicdan\FiberAsync\Api\Promise;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function App\view;

class HomeController
{
    public function index(Request $request, Response $response, $args)
    {
        $startTime = microtime(true);
        Promise::all([
            delay(1),
            delay(2),
            delay(3),
        ])->await();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        return view($response, 'home', ['executionTime' => $executionTime]);
    }
}
