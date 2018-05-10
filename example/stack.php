<?php

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

function auth($apikey, $secret)
{
    return Middleware::mapRequest(function (RequestInterface $request) use ($apikey, $secret) {
        $request = $request->withHeader('key', $apikey);
        $request = $request->withHeader('secret', $secret);
        return $request;
    });
}
function logRequest($logger)
{
    return Middleware::mapRequest(function (RequestInterface $request) use ($logger) {
        $logger->log($request);
        return $request;
    });
}
function logResponse($logger)
{
    return Middleware::mapResponse(function (ResponseInterface $response) use ($logger) {
        $logger->log($response);
        return $response;
    });
}
$logger = new PageScreenClient\Middleware\Logger;
$stack = HandlerStack::create();
// $stack->push(auth(getenv('PS_APIKEY'), getenv('PS_SECRET')));
$stack->push(logRequest($logger));
$stack->push(logResponse($logger));
return $stack;
