<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ========== ADD THIS CODE FOR OPTIONS REQUESTS ==========
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    
    // Allow only your specific frontend origin
    $allowedOrigins = [
        'https://onlineadminmanager.netlify.app',
        // Add localhost for development if needed:
        // 'http://localhost:3000',
    ];
    
    if (in_array($origin, $allowedOrigins)) {
        header("Access-Control-Allow-Origin: " . $origin);
    } else {
        // Or use wildcard if you want (less secure):
        header("Access-Control-Allow-Origin: *");
    }
    
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true"); // ← THIS IS CRITICAL
    header("Access-Control-Max-Age: 86400");
    http_response_code(204); // NO CONTENT
    exit();
}
// ========== END OF ADDED CODE ==========

// Regular CORS headers for actual requests
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = ['https://onlineadminmanager.netlify.app'];

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $origin);
    header("Access-Control-Allow-Credentials: true");
} else {
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());