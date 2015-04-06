Welcome to PostTypes Plugin documentation!
=====================================

Introduction
------------

The PostTypes plugin is smoothly integrated by the [CakeManager Plugin](https://github.com/cakemanager/cakephp-cakemanager).
With this plugin you are able to easily create CRUD for your admin-area. That means you don't have to spend time in building 
your admin-area and managing your app!

Look at this piece of code:

        $this->PostTypes->register('Bookmarks', [
            'menu' => true,
            'tableFields' => [
                'user_id',
                'title',
                'url',
            ],
            'formFields' => [
                'id',
                'title',
                'url',
            ],
            'filters' => [
                'title',
                'url'
            ]
        ]);

Good luck and happy coding ;)


Get the code
------------
The [source (http://github.com/cakemanager/cakephp-posttypes)](http://github.com/cakemanager/cakephp-posttypes) is available on Github.

Branches
--------
**master** is our master-branche. This will contain the latest version and latest documentation.

**develop** will be used to develop on. Warning; this branche may contain non-stable code. You can always do pull-requests on this branche so new stuff will be available on new releases.

