PostTypes-Component
====================

The `PostTypesComponent` is the essential part of the plugin. This components is used to register new types,
and handles the requests when using them.

[doc_toc]


Loading
-------

You can load the component in your AppController:

            public function initialize() {
                // code
                $this->loadComponent('PostTypes.PostTypes');
            }

            
Configurations
--------------

There are few configurations for the component.

### formFieldOptions

This array can be used to set the default values of every set FormField. For example:

When using the following configuration:

        $this->PostTypes->config('formFieldOptions', [
            'class' => 'default-class'
        ]);
        
And adding a formField like this:

        $this->PostTypes->register('CustomType', [
            'formFields' => [
                'type01',
                'type02'
            ]
        ]);

The formFields `type01` and `type02` will both contain the class `default-class` as default (unless they are changed in 
the `register`-method.

### tableFieldOptions

This option has the same goal as above, but is used for the tableFields.


Usage
-----

Usage for the following methods can be done in your `AppController`. Use the `initPostTypes`-method for that. That method
will be called automatically by the component.

### Register

Registering PostTypes can be done by the `register`-method. Example:

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
        
This example will register a new PostType named `Blogs`. When registering PostTypes you can use multiple options:

- `menu` - If a menu-item should be generated at the admin-area. Default `false`.
- `model` - String of the model to use. Example: `Plugin.Blogs` or `Blogs`. Default stripped from the name (first parameter).
- `contain` - Contain-key used by queries. Default an empty array (so default all relations will be taken).
- `tableFields` - Array with a list of fields to use for the table (on the index-page). Will be documented later on.
- `formFields` - Array with a list of fields to use for the form (add and edit-page). Will be documented later on.
- `alias` - Alias for the PostType. Default stripped from the name (first parameter).
- `name` - Just a copy of the first parameter of the `register`-method. You have noting to do with that ;).
- `type` - Type name of the PostType. Is the singularized version of the name (first parameter).
- `filters` - Used filters for the PostType's index-page ([Read more about filters](http://cakemanager.org/docs/utils/1.0/components/search/)).
- `actions` - Array with all actions to use. For example if you don't want the view-action, set the key of `view` to `false`.
Default te PostTypes has all types of actions (index, view, add, edit, delete).
- `views` - Can be used to set different view-files. Use the action-name as key, and the path to the view as value (just as on the `render`-method 
of the Controller).

#### tableFields

The `tableFields` array contains a list of fields to use for the table on the index page. For example:

        'tableFields' => [
            'user_id' => [
                'get' => 'created_by.email'
            ],
            'title',
            'content',
        ],

The following options are available per field:

- `hide` - Boolean if the field should be hidden. Default set to `false` (of course ;)).
- `get` - Use to change the `get`-value. For example: using the email of a user from a post: `'get' => 'user.email'`.
- `before` - Used to put some html before the value.
- `after` - Used to put some html after the value.

#### formFields

The `formFields` array contains a list of fields to use for the form on the add- and edit page. The keys represent the input-names,
and the values are an array with options like http://book.cakephp.org/3.0/en/views/helpers/form.html#options.


#### Registering on the fly

You can register PostTypes on the fly using the `Configure`-class. Example:

        Configure::write('PostTypes.register.Blogs', []);

All options listed above can be set in the empty array.


### Remove

Removing a PostType is easy using the `remove`-method. Example:

        $this->PostTypes->remove('Blogs');
        
        
### Check

With the `check`-method you are able to check if a PostType is already registered. Example:

        $this->PostTypes->check('Blogs');
        
This method will return `true` or `false`.


### Get

If you want the data of a specific PostType, you can use the `get`-method. Example:

        $this->PostTypes->get('Blogs');
        
This method will return an array with all settings of the PostType.


Controller methods
------------------

The `PostTypesComponent` also contains some methods that are useful for the `PostTypeController` in the admin-panel. 
They will documented here.

### mapTableFields

This method maps the given tableFields. In this method, the default values (given in the config) will be merged. 


### mapFormFields

This method maps the given formFields. In this method, the default values (given in the config) will be merged.


### postTypeFinder

The `postTypeFinder` method is able to find the current PostType out of the given url. The input-variable is a request
object. The string of the PostType will be returned, or an empty string if there's nothing found.
