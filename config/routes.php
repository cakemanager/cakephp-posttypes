<?php

use Cake\Routing\Router;

Router::prefix('admin', function ($routes) {
    $routes->plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {
        $routes->fallbacks('InflectedRoute');
    });
    $routes->fallbacks('InflectedRoute');
});

Router::plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {
    $routes->fallbacks('InflectedRoute');
});

Router::connect('/blabla/:action/**', ['plugin' => 'PostTypes', 'prefix' => 'admin', 'controller' => 'PostTypes']);

