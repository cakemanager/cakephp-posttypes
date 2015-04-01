Quick Start
===========

In this tutorial we are going to install the PostTypes plugin and add our first PostTye.

[doc_toc]

Requirements
------------

- A fresh copy of CakePHP 3.x
- Composer
- Installed CakeManager plugin (http://cakemanager.org/docs/1.0/installation/)

Using Composer
--------

You can install this plugin into your CakePHP application using composer.

The recommended way to install composer packages is:

        composer require cakemanager/cakephp-posttypes:1.0.x-dev

For the stable use, use the `1.0` branch.

Loading the plugin
-------------

You will need to add the following line to your application's `config/bootstrap.php` file:

        Plugin::load('PostTypes', ['bootstrap' => true, 'routes' => true]);


Adding the components
--------------------

Lets load the `PostTypesComponent` into your `AppController`:

        public function initialize()
        {
            // code

            $this->loadComponent('PostTypes.PostTypes');

            // code
        }

Now you are ready to add your PostTypes. 

Adding your first PostType
--------------------------

We want to manage our blogs. We got this sql-code:

        CREATE TABLE `blogs` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NULL DEFAULT NULL,
            `title` VARCHAR(256) NULL DEFAULT NULL,
            `content` TEXT NULL,
            `created` DATETIME NULL DEFAULT NULL,
            `modified` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        )
        COLLATE='latin1_swedish_ci'
        ENGINE=InnoDB
        ;

Now it's time to add our code. For that we will use the `initPostTypes` method inside our `AppController`. This method is automatically 
called by the `PostTypesComponent`.

        public function initPostTypes()
        {
            $this->loadModel('CakeManager.Users');
 
            $this->PostTypes->register('Blogs', [
                'menu' => true,
                'tableFields' => [
                    'user_id' => [
                        'get' => 'created_by.email'
                    ],
                    'title',
                    'content',
                ],
                'formFields' => [
                    'id',
                    'user_id' => [
                        'options' => $this->Users->find('list')
                    ],
                    'title',
                    'content',
                ],
                'filters' => [
                    'title',
                    'user_id' => [
                        'options' => $this->Users->find('list')
                    ]
                ],
            ]);
        }

Lets walk through this code...

- Using `$this->PostTypes->register($name, $options);` will register a new PostType. The first parameter will be the name 
of the type, the second one will contain its settings.

- With `'menu' => true,` a menu-item will be added to the admin-area of your application.

- The `tableFields` list contains an array with all fields that should be displayed on your index-page.

- The `formFields` list contains an array with all fields for your form (add and edit). Using the `options` option will 
allow you to create a select-box. All of the options you should use normally can be added here.

- The `filters` array gives you the possibility to add filters to your index-page. For more: read about the 
[Utils Plugin](http://cakemanager.org/docs/utils/1.0/components/search/)

Of course there are much more options available. They are all documented on this website.

Done
----

Now you are able to login via `/login`. When you are logged in you are able to manage your blogs. You are able to get a list,
with your own filters, add new ones, edit them and delete unwanted posts. Good luck, and happy coding :).

Further reading
---------------

Here are some suggestions related to this section:

- Read the [Utils Docs](/docs/utils/1.0/) for documentation about all components and behaviors from the [Utils Plugin](https://github.com/cakemanager/cakephp-utils).
