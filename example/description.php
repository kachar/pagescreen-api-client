<?php

return [
    // 'baseUri' => 'http://httpbin.org/',
    'operations' => [
        'capture' => [
            'httpMethod' => 'GET',
            'uri' => '/v1/capture{/ID}.json',
            'responseModel' => 'Model\Capture',
            'parameters' => [
                'ID' => [
                    'type' => 'string',
                    'location' => 'uri',
                ],
            ],
        ],
        'url' => [
            'httpMethod' => 'GET',
            'uri' => '/v1/url{/ID}.json',
            'responseModel' => 'Model\Url',
            'parameters' => [
                'ID' => [
                    'type' => 'string',
                    'location' => 'uri',
                ],
            ],
        ],
    ],
    'models' => [
        'Model\Envelope' => [
            'type' => 'object',
            'properties' => [
                'status' => [
                    'type' => 'int',
                ],
                'requestId' => [
                    'type' => 'string',
                ],
                'data' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                    ],
                ],
                'count' => [
                    'type' => 'int',
                ],
            ],
            'additionalProperties' => false,
        ],
        'Model\Capture' => [
            'type' => 'object',
            'extends' => 'Model\Envelope',
            'properties' => [
                'data' => [
                    'location' => 'json',
                    'additionalProperties' => false,
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'location' => 'json',
                        'additionalProperties' => false,
                        'properties' => [
                            'id' => [
                                'type' => 'string',
                            ],
                            'token' => [
                                'type' => 'string',
                            ],
                            'requested_on' => [
                                'type' => 'string',
                            ],
                            'url' => [
                                'type' => 'object',
                                '$ref' => 'Model\Url',
                            ],
                        ],
                    ],
                ],
            ],
            'additionalProperties' => false,
            // 'additionalProperties' => [
            //     'location' => 'json',
            // ],
        ],
        'Model\Url' => [
            'type' => 'object',
            'extends' => 'Model\Envelope',
            'properties' => [
                'id' => [
                    'type' => 'string',
                    'required' => true,
                ],
                'url' => [
                    'type' => 'string',
                    'required' => true,
                ],
                'title' => [
                    'type' => 'string',
                    'required' => true,
                ],
                'description' => [
                    'type' => 'string',
                    'required' => true,
                ],
            ],
            'additionalProperties' => false,
        ],
    ],
];
