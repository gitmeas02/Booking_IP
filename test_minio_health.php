<?php
// Simple MinIO health check and image listing
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// MinIO connection details
$minioHost = 'localhost:9000';
$bucket = 'ownerimages';
$minioUser = 'admin';
$minioPassword = 'password123';

// Test MinIO connection
function testMinioConnection($host, $bucket) {
    $url = "http://{$host}/{$bucket}/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'success' => $httpCode === 200,
        'http_code' => $httpCode,
        'error' => $error
    ];
}

// Test specific image existence
function testImageExists($host, $bucket, $imagePath) {
    $url = "http://{$host}/{$bucket}/{$imagePath}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'url' => $url,
        'success' => $httpCode === 200,
        'http_code' => $httpCode,
        'content_type' => $contentType,
        'content_length' => $contentLength,
        'error' => $error
    ];
}

// Test images from the failing URLs
$testImages = [
    'room-images/room_1_1752581872_687646f00d30f.jpg',
    'room-images/room_2_1752581874_687646f22fc83.jpg',
    'room-images/room_3_1752581874_687646f281f57.jpg',
    'room-images/room_4_1752581874_687646f2d4e8b.jpg',
    'room-images/room_9_1752611767_6876bbb759f19.jpg',
    'owner_applications_images/dummy1.jpg',
    'owner_applications_images/dummy2.jpg',
    'owner_applications_images/dummy3.jpg',
    'owner_applications_images/dummy4.jpg'
];

$results = [
    'minio_connection' => testMinioConnection($minioHost, $bucket),
    'bucket_url' => "http://{$minioHost}/{$bucket}/",
    'images' => []
];

foreach ($testImages as $imagePath) {
    $results['images'][] = testImageExists($minioHost, $bucket, $imagePath);
}

echo json_encode($results, JSON_PRETTY_PRINT);
?>
