<?php
// ========== MINIMAL CORS HANDLING ==========
// Handle OPTIONS requests BEFORE anything else
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Clear any existing output
    if (ob_get_length()) ob_clean();
    
    // Set ONLY these headers
    header("Access-Control-Allow-Origin: https://onlineadminmanager.netlify.app");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 86400");
    header_remove('Content-Type'); // Remove content-type for OPTIONS
    http_response_code(204);
    exit();
}
// ========== END CORS ==========

// Now continue with Laravel
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$response = $app->handleRequest(Request::capture());

// Add CORS headers to regular responses
if ($response !== null) {
    if (!$response->headers->has('Access-Control-Allow-Origin')) {
        $response->headers->set('Access-Control-Allow-Origin', 'https://onlineadminmanager.netlify.app');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
    }

    $response->send();
}