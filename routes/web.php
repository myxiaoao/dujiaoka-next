<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
$routesDir = ['common'];

foreach ($routesDir as $dir) {
    foreach (glob(__DIR__.'/'.$dir.'/*.php') as $routerFile) {
        require_once $routerFile;
    }
}
