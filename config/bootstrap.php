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

/**
 * When register a PostType and fill no formfields, autmatically all fields will be set.
 * This array contains columns to ingore always.
 */
Configure::write('PostTypes.FilteredColumns', [
    'created',
    'modified',
    'created_by',
    'modified_by'
]);