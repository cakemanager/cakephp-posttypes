<?php

use Cake\Core\Configure;

/**
 * The default PostTypesViews to use for admin-section
 * Default for the CakeManager itself, you can change it for your own views
 */
Configure::write('PostTypes.AdminPostTypeViews', [
    'index'        => 'PostTypes./Admin/PostTypes/index',
    'view'         => 'PostTypes./Admin/PostTypes/view',
    'add'          => 'PostTypes./Admin/PostTypes/add',
    'edit'         => 'PostTypes./Admin/PostTypes/edit',
]);