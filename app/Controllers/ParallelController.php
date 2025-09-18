<?php

namespace App\Controllers;

use Library\Defer\Parallel;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function App\view;

class ParallelController
{
    public function index(Request $request, Response $response)
    {
        $startTime = microtime(true);
        Parallel::all([
            fn() => sleep(1),
            fn() => sleep(1),
            fn() => sleep(1),
        ]);
        $totalTime = microtime(true) - $startTime;

        return view($response, 'parallel', ['totalTime' => $totalTime]);
    }
}
