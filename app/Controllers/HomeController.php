<?php

namespace App\Controllers;

use Rcalicdan\FiberAsync\Api\Timer;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function App\view;

class HomeController
{
    public function index(Request $request, Response $response, $args)
    {
        $start_time = microtime(true);

        $results = run_all([
            'task_A' => function () {
                Timer::sleep(2);
                return "Task A (slept for 2 seconds) finished successfully.";
            },
            'task_B' => function () {
                Timer::sleep(3);
                return "Task B (slept for 3 seconds) finished successfully.";
            }
        ]);

        $result_A = $results['task_A'];
        $result_B = $results['task_B'];

        $end_time = microtime(true);
        $total_duration = $end_time - $start_time;

        return view($response, 'home', [
            'total_duration' => $total_duration,
            'result_A' => $result_A,
            'result_B' => $result_B,
        ]);
    }
}
