<?php

use Illuminate\Support\Facades\Route;

// ========== API ROUTE'LARI ==========
Route::post('/api/admin/login', function (\Illuminate\Http\Request $request) {
    // ... login kodi
});

// ========== ODDIY ROUTE'LAR (index.html bo'lmasa) ==========
$routes = [
    '/',
    '/admin',
    '/login',
    'admin/employees',
    'admin/products',
    'admin/inventory',
    'admin/sales',
    'admin/website',
    'admin/profile',
];

foreach ($routes as $route) {
    Route::get($route, function () {
        $htmlPath = public_path('index.html');
        
        if (file_exists($htmlPath)) {
            return response(file_get_contents($htmlPath))
                ->header('Content-Type', 'text/html');
        }
        
        // index.html yo'q bo'lsa, JSON qaytar
        return response()->json([
            'app' => 'Online Manager Backend',
            'frontend' => 'https://onlineadminmanager.netlify.app',
            'api' => [
                'login' => 'POST /api/admin/login',
                'health' => 'GET /api/health'
            ],
            'note' => 'Frontend alohida Netlify-da. Backend faqat API uchun.'
        ]);
    });
}