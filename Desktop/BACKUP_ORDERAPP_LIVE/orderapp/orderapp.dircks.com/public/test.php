<?php
// https://orderapp.dircks.com/test.php?key=dp07
// Define the base path and include the necessary files
$basePath = realpath(__DIR__ . '/../');

require $basePath . '/vendor/autoload.php';
$app = require_once $basePath . '/bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;

$app->make(Kernel::class)->bootstrap();

// Define a secret key
$secretKey = 'dp07';

// Check if the secret key is provided and matches
if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    echo 'Access denied.';
    exit;
}

try {
    // Clear application cache
    Artisan::call('cache:clear');
    echo 'Application cache cleared.<br>';

    // Clear route cache
    Artisan::call('route:clear');
    echo 'Route cache cleared.<br>';

    // Clear config cache
    Artisan::call('config:clear');
    echo 'Config cache cleared.<br>';

    // Clear view cache
    Artisan::call('view:clear');
    echo 'View cache cleared.<br>';

    // Recreate config cache
    Artisan::call('config:cache');
    echo 'Config cache created.<br>';

    // Recreate route cache
    Artisan::call('route:cache');
    echo 'Route cache created.<br>';

    // Recreate view cache
    Artisan::call('view:cache');
    echo 'View cache created.<br>';

    // Clear compiled files
    Artisan::call('clear-compiled');
    echo 'Compiled files cleared.<br>';

    // Optimize the framework for better performance
    Artisan::call('optimize');
    echo 'Framework optimized.<br>';
} catch (Exception $e) {
    echo 'An error occurred: ' . $e->getMessage();
}
