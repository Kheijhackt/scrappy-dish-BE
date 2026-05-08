<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    $path = base_path('README.md');
    
    $content = file_exists($path) ? file_get_contents($path) : "# File not found";
    
    return view('welcome', [
        'htmlContent' => Str::markdown($content)
    ]);
});
