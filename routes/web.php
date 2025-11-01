<?php

use Illuminate\Support\Facades\Route;

// Load dujiaoka routes from common directory
$routesDir = ['common'];

foreach ($routesDir as $dir) {
    foreach (glob(__DIR__ . '/' . $dir . '/*.php') as $routerFile) {
        require_once $routerFile;
    }
}
