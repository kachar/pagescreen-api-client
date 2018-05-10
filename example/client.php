<?php

namespace Client;

chdir(dirname(__DIR__));

require 'config/env.php';
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use JsonMapper;
use PageScreenClient\ApiClientFactory;

$client = (new ApiClientFactory)->factory(include 'stack.php');
$description = new Description(include 'description.php');
$guzzleClient = new GuzzleClient($client, $description);

$result = $guzzleClient->capture(['ID' => 13854]);
// $result = $guzzleClient->url();
// var_dump($result->toArray());
// $result = $guzzleClient->url(['ID' => 980]);
// $result = $guzzleClient->bin(['foo' => 'bar', 'bar' => 'baz']);
$mapper = new JsonMapper;
$mapper->bExceptionOnUndefinedProperty = true;
var_dump($result->toArray());

$contactObject = $mapper->map(
    json_decode(json_encode($result->toArray()['data'][0])),
    new \PageScreenClient\Entity\Capture()
);
var_dump($contactObject);
