<?php

namespace App\Controllers;

use React\EventLoop\Loop;
use React\Promise\Promise;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function App\view;
use function React\Async\async;
use function React\Async\await;
use function React\Promise\all;

class ReactController
{
    public function delay($seconds)
    {
        return new Promise(function ($resolve) use ($seconds) {
            Loop::addTimer($seconds, $resolve);
        });
    }

    public function index(Request $request, Response $response)
    {
        $totalTime = async(function () {
            $startTime = microtime(true);
            await(all([
                $this->delay(1),
                $this->delay(1),
                $this->delay(1),
            ]));
            $endTime = microtime(true);
            $totalTime = $endTime - $startTime;
            return $totalTime;
        })();

        return view($response, "react-test", ["totalTime" => await($totalTime)]);
    }
}
