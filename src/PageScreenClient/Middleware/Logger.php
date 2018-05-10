<?php

namespace PageScreenClient\Middleware;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Logger
{
    public function log($entry)
    {
        if ($entry instanceof RequestInterface) {
            return $this->logRequest($entry);
        }
        if ($entry instanceof ResponseInterface) {
            return $this->logResponse($entry);
        }
        echo $entry;
    }
    private function logRequest(RequestInterface $request)
    {
        echo (string) $request->getMethod() . ' ' . $request->getUri();
        // echo json_encode([
        //     'uri' => (string) $request->getUri(),
        //     // 'body' => $request->getBody(),
        //     // 'headers' => $request->getHeaders(),
        // ]);
        echo PHP_EOL;
    }
    private function logResponse(ResponseInterface $response)
    {
        echo (string) $response->getBody();
        // echo json_encode([
        //     'body' => (string) $response->getBody(),
        //     'headers' => $response->getHeaders(),
        // ]);
        echo PHP_EOL;
    }
}
