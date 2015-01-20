<?php

use Cake\Routing\Router;

Router::prefix('admin', function ($routes) {
    $routes->plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {

        // these curls are not working... why :S
        $routes->connect(
                '/:type/:action/*', ['controller' => 'PostTypes'], ['pass' => ['type']]
        );

        $routes->fallbacks('InflectedRoute');
    });
    $routes->fallbacks('InflectedRoute');
});

Router::prefix('api', function ($routes) {
    $routes->extensions(['json']);

    $routes->plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {

        // these curls are not working... why :S
        $routes->connect(
                '/:type/:action/*', ['controller' => 'PostTypes'], ['pass' => ['type']]
        );

        $routes->fallbacks('InflectedRoute');
    });
    $routes->fallbacks('InflectedRoute');
});

Router::plugin('PostTypes', ['path' => '/posttypes'], function ($routes) {
    $routes->fallbacks('InflectedRoute');
});

