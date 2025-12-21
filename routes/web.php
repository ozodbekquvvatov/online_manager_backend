<?php

use Illuminate\Support\Facades\Route;

// routes/web.php da
Route::post('/api/admin/login', function (\Illuminate\Http\Request $request) {
    // To'g'ri login logikasi yozing
    return response()->json([
        'success' => true,
        'token' => 'test-token-' . time(),
        'user' => [
            'id' => 1,
            'name' => 'Admin',
            'email' => $request->email,
            'role' => 'admin'
        ]
    ]);
});

