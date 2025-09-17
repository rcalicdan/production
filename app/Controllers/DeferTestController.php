<?php

namespace App\Controllers;

use Library\Defer\Defer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\view;

class DeferTestController
{
    public function showTest(Request $request, Response $response): Response
    {
        $testId = uniqid('test_');
        $logFile = sys_get_temp_dir() . '/defer_test.log';
    
        if (file_exists($logFile)) {
            unlink($logFile);
        }
        
        $this->log($logFile, "START: {$testId} - Response preparation started");
        
        Defer::terminate(function() use ($logFile, $testId) {
            $this->log($logFile, "TERMINATE_1: {$testId} - First task executing after response");
            sleep(2); 
            $this->log($logFile, "TERMINATE_1: {$testId} - Heavy work completed");
        });
        
        Defer::terminate(function() use ($logFile, $testId) {
            $this->log($logFile, "TERMINATE_2: {$testId} - Second task (cleanup simulation)");
            usleep(500000); 
            $this->log($logFile, "TERMINATE_2: {$testId} - Cleanup completed");
        });
        
        Defer::terminate(function() use ($logFile, $testId) {
            $this->log($logFile, "TERMINATE_3: {$testId} - Final task");
            $this->log($logFile, "COMPLETE: {$testId} - All terminate tasks finished");
        });
        
        $this->log($logFile, "RESPONSE: {$testId} - About to send response to client");
        
        return view($response, 'defer-test', [
            'testId' => $testId,
            'logFile' => $logFile
        ]);
    }
    
    public function getStatus(Request $request, Response $response): Response
    {
        $logFile = sys_get_temp_dir() . '/defer_test.log';
        
        $data = [
            'exists' => file_exists($logFile),
            'content' => file_exists($logFile) ? file_get_contents($logFile) : 'No log file found',
            'size' => file_exists($logFile) ? filesize($logFile) : 0,
            'time' => date('Y-m-d H:i:s')
        ];
        
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    private function log(string $file, string $message): void
    {
        $timestamp = date('H:i:s.') . substr(microtime(), 2, 3);
        file_put_contents($file, "[{$timestamp}] {$message}\n", FILE_APPEND | LOCK_EX);
    }
}