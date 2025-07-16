<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$response = [
    'message' => 'Test server is working!',
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'path' => $_SERVER['REQUEST_URI'],
    'hotels' => [
        [
            'id' => 1,
            'name' => 'Test Hotel 1',
            'location' => 'Test Location 1',
            'price' => 100
        ],
        [
            'id' => 2,
            'name' => 'Test Hotel 2',
            'location' => 'Test Location 2',
            'price' => 200
        ]
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
