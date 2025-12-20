<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});
Route::get('admin/employees', function () {
    return file_get_contents(public_path('index.html'));
});

Route::get('admin/products', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/calculators', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/attendance', function () {
    return file_get_contents(public_path('index.html'));    });  
Route::get('admin/payroll', function () {
    return file_get_contents(public_path('index.html'));}); 

Route::get('admin/inventory', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/sales', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/customers', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/expenses', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/accounting', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/reports', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/analytics', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/website', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/settings', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/profile', function () {
    return file_get_contents(public_path('index.html'));});
Route::get('/admin', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('/login', function () {
    return file_get_contents(public_path('index.html'));});



Route::post('/test-simple-login', function (Illuminate\Http\Request $request) {
    // Log the incoming request
    Log::info('Test login attempt', [
        'email' => $request->email,
        'has_password' => !empty($request->password),
        'ip' => $request->ip()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Login endpoint works',
        'received' => $request->only('email', 'password'),
        'timestamp' => now()
    ]);
});