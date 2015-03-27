<?php

use Cake\Routing\Router;

Router::prefix('admin', function ($routes) {
    $routes->plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {

        $routes->connect(
                '/:type/:action/*', ['controller' => 'PostTypes'], ['pass' => ['type']]
        );

        $routes->fallbacks('InflectedRoute');
    });
    $routes->fallbacks('InflectedRoute');
});

Router::prefix('api', function($routes) {
    $routes->plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {
        $routes->extensions(['json']);

        $routes->connect(
                '/:type/:action/*', ['controller' => 'PostTypes'], ['pass' => ['type']]
        );

        $routes->resources('PostTypes');

        $routes->fallbacks('InflectedRoute');
    });
});

Router::plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {
    $routes->fallbacks('InflectedRoute');
});

