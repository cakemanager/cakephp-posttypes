Component
=========

The component is the important part of the plugin.
Via the `PostTypes`Component you can easily register, check, and get PostTypes.

Register
--------

You can register an PostType this way:

```php

        $this->PostTypes->register('Bookmarks', [
            'menu'        => true,
            'tableFields' => [
                'id',
                'user_id',
                'title',
                'url',
            ],
            'contain'     => ['Tags', 'Users'],
        ]);

```

This code is based on the first tutorial of the CakePHP 3.x documentation.
What does this code do?

We register the PostType with `$this->PostTypes->register()`
The first parameter `Bookmarks` is the name of our type.
Now we can set multiple options:

| Option           | Type              | Description  |
| :------------------|:-----------------| :-----|
| menu      | bool    | Use this option to enable a menu-item for the admin-section  |
| model     | string    | Give the current model. Default set to the type-name (`BookMarks` in our case), but you can use this for plugins like `MyPlugin.Tags` |
| contain        | array    | This is the same `contain` as you use in your queries. This allows you to use and load data from associations |
| tableFields         | array    | This is the list for the fields to use in our table.  |
| formFields          | array    | This is the list for the generated form to add and edit your data  |
| alias        | string    | Used for a nice naming in your admin-dashboard  |
| name         | string    | You don't need to set this, we will do that for you. It's the name you gave at first argument ;) |
| type      | string    | Nothing more than the singularized version of the name ;) |

Check
-----

Sometimes you want to check if a type is set. This code:

```php
    $this->PostTypes->check('Bookmarks');
```

Will return true if the type exists. False if it doesn't.

Get
---

The PostType contains a lot of settings and you want to get it? Use:

```php
    $this->PostTypes->get('Bookmarks');
```

Note: You don't have to use the `check()`-method first, we do it already for you ;)