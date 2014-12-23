Installation
============

Composer
--------

Oops, the composer-install is not ready yet... Sorry!

Loading
-------

Load the plugin in your `config/bootstrap.php` file

```php
Plugin::load('PostTypes', ['bootstrap' => true, 'routes' => true, 'autoload' => true]);
```
Note: Load this plugin AFTER the CakeManager-plugin!

Using
-----

Now we want to use the PostTypes-plugin. Load the plugin in your `AppController`:

```php
 public function initialize() {
        //
        $this->loadComponent('CakeManager.Manager'); // The CakeManager
        $this->loadComponent('PostTypes.PostTypes'); // Added; The PostType
        //
    }
```