<?php

namespace App\Controllers;

use Library\Defer\Parallel;
use Library\Defer\Process\ProcessManager;
use Library\Defer\Config\ConfigLoader;
use Library\Defer\Utilities\SystemUtilities;
use Library\Defer\Logging\BackgroundLogger;
use Library\Defer\Serialization\CallbackSerializationManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function App\view;

class ParallelController
{
    public function index(Request $request, Response $response)
    {
        $config = ConfigLoader::getInstance();
        $systemUtils = new SystemUtilities($config);
        $logger = new BackgroundLogger($config);
        $processManager = new ProcessManager($config, $systemUtils, $logger);
        $serialization = new CallbackSerializationManager();

        $platformInfo = $processManager->getPlatformInfo();
        $capabilities = $processManager->testCapabilities(false, $serialization);

        return view($response, 'debug', [
            'platformInfo' => $platformInfo,
            'capabilities' => $capabilities,
        ]);
    }

    public function parallelTest(Request $request, Response $response)
    {
        $startTime = microtime(true);

        try {
            $results = Parallel::all([
                fn() => ['task' => 1, 'sleep' => sleep(1), 'result' => 'Task 1 completed'],
                fn() => ['task' => 2, 'sleep' => sleep(1), 'result' => 'Task 2 completed'],
                fn() => ['task' => 3, 'sleep' => sleep(1), 'result' => 'Task 3 completed'],
            ]);

            $totalTime = microtime(true) - $startTime;

            return view($response, 'parallel', [
                'totalTime' => $totalTime,
                'results' => $results,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            $totalTime = microtime(true) - $startTime;

            return view($response, 'parallel', [
                'totalTime' => $totalTime,
                'error' => $e->getMessage(),
                'success' => false,
            ]);
        }
    }
}
