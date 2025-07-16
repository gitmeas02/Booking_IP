<?php
// Simple image proxy without Laravel
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get the image path from URL
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove '/api/images/' prefix to get actual image path
$imagePath = str_replace('/api/images/', '', $path);

// MinIO connection details
$minioHost = 'minio:9000';
$minioUser = 'admin';
$minioPassword = 'password123';
$bucket = 'ownerimages';

// Construct MinIO URL
$minioUrl = "http://{$minioHost}/{$bucket}/{$imagePath}";

// Create context for MinIO authentication
$auth = base64_encode("{$minioUser}:{$minioPassword}");
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Basic {$auth}\r\n",
        'timeout' => 10
    ]
]);

// Try to fetch image from MinIO
$imageData = @file_get_contents($minioUrl, false, $context);

if ($imageData === false) {
    // If MinIO fails, try direct file access
    $localPath = "/var/www/storage/app/public/{$imagePath}";
    if (file_exists($localPath)) {
        $imageData = file_get_contents($localPath);
    }
}

if ($imageData === false) {
    // Return 404 if image not found
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Image not found', 'path' => $imagePath]);
    exit;
}

// Determine content type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_buffer($finfo, $imageData) ?: 'application/octet-stream';
finfo_close($finfo);

// Set appropriate headers
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . strlen($imageData));
header('Cache-Control: public, max-age=31536000'); // Cache for 1 year

// Output image data
echo $imageData;
?>
