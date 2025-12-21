<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});
Route::get('admin/employees', function () {
    return file_get_contents(public_path('index.html'));
});
Route::get('admin/products', function () {
    return file_get_contents(public_path('index.html'));});
Route::get('admin/inventory', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/sales', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/website', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('admin/profile', function () {
    return file_get_contents(public_path('index.html'));});
Route::get('/admin', function () {
    return file_get_contents(public_path('index.html'));});

Route::get('/login', function () {
    return file_get_contents(public_path('index.html'));});



