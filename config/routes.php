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

// these curls are not working... why :S
Router::connect(
        '/posttypes/:type/:action', [
    'admin'      => true,
    'plugin'     => 'PostTypes',
    'controller' => 'post_types',
        ], [
    'pass' => ['type'],
    'type' => '/d+',
        ]
);

// these curls are not working... why :S
Router::connect(
        '/textpath', [
    'prefix'     => false,
    'plugin'     => false,
    'controller' => 'Bookmarks',
    'action'     => 'index',
]);
