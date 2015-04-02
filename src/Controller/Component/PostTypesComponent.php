<?php
/**
 * CakeManager (http://cakemanager.org)
 * Copyright (c) http://cakemanager.org
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) http://cakemanager.org
 * @link          http://cakemanager.org CakeManager Project
 * @since         1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace PostTypes\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * PostTypes component
 */
class PostTypesComponent extends Component
{
    /**
     * Default configuration.
     * @var array
     */
    protected $_defaultConfig = [
        'formFieldOptions' => [
        ],
        'listFieldOptions' => [
            'hide' => false,
            'get' => false,
            'before' => '',
            'after' => '',
        ],
    ];

    /**
     * Controller
     * @var Controller
     */
    protected $Controller = null;

    /**
     *
     * @var array list of PostTypes
     */
    protected static $_postTypes = [];

    /**
     * __construct
     *
     * @param \Cake\Controller\ComponentRegistry $registry Registry.
     * @param array $config Configurations.
     */
    public function __construct($registry, array $config = array())
    {
        parent::__construct($registry, $config);

        $this->setController($this->_registry->getController());
    }

    /**
     * initialize
     *
     * @param array $config Configurations.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setController($this->_registry->getController());

        $this->_registerFromConfigure();
    }

    /**
     * setController
     *
     * Setter for the Controller property.
     *
     * @param \Cake\Controller\Controller $controller Controller.
     * @return void
     */
    public function setController($controller)
    {
        $this->Controller = $controller;
    }

    /**
     * BeforeFilter Event
     *
     * This method will check if the `initPostTypes`-method exists in the
     * `AppController`. That method contains PostTypes to add.
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeFilter($event)
    {
        $this->setController($event->subject());

        if ($this->Controller->Manager->prefix('admin')) {
            if (method_exists($this->Controller, 'initPostTypes')) {
                $this->Controller->initPostTypes($event);
            }
        }
    }

    /**
     * register
     *
     * Registers a new posttype.
     *
     * @param string $name of the type
     * @param array $options for the type
     *
     * ### OPTIONS
     * - menu   boolean     set if you want a menu-item for the admin-area
     * - model  string      is the model-name to use
     * - fields mixed       use an array to define the fields to use, set to false to use the postTypeFields-method in your table-class
     * - alias  string      the alias
     *
     * @return void
     */
    public function register($name, $options = [])
    {
        $_options = [
            'menu' => false,
            'model' => ucfirst($name),
            'contain' => [],
            'tableFields' => [],
            'formFields' => [],
            'alias' => $name,
            'name' => ucfirst($name),
            'type' => Inflector::singularize(ucfirst($name)),
            'filters' => [],
            'actions' => [
                'view' => true,
                'edit' => true,
                'delete' => true,
                'add' => true,
            ],
            'views' => [
                'index' => false,
                'view' => false,
                'add' => false,
                'edit' => false,
            ]
        ];

        $name = ucfirst($name);

        $options = Hash::merge($_options, $options);

        // We have to map the fields-array if it's not false
        if ($options['tableFields']) {
            $options['tableFields'] = $this->mapTableFields($options['tableFields']);
        }

        // We have to map the fields-array if it's not false
        if ($options['formFields']) {
            $options['formFields'] = $this->mapFormFields($options['formFields']);
        }

        // Adding menu-items if set
        if ($options['menu']) {
            $this->_addMenu($name, $options);
        }

        $list = self::$_postTypes;

        $list[$name] = $options;

        self::$_postTypes = $list;
    }

    /**
     * check
     *
     * Checks if the given posttype is registerd.
     *
     * @param string $name of the posttype.
     * @param array $options Options.
     *
     * ### Options
     * The following options are available:
     *
     * - exception - boolean if the method should return an exception or not.
     *
     * @return bool if the type exists.
     */
    public function check($name, $options = [])
    {
        $_options = [
            'exception' => false,
        ];

        $options = array_merge($_options, $options);

        $name = ucfirst($name);

        if ($options['exception']) {
            if (!(key_exists($name, self::$_postTypes))) {
                throw new \Exception("The PostType is not known");
            }
        }

        return (key_exists($name, self::$_postTypes));
    }

    /**
     * get
     *
     * Returns the options of the posttype
     * If the posttype is not set the method will return bool false
     *
     * If there is no PostType found, a bool `false` will be returned.
     *
     * @param string $name Name of the PostType to return.
     * @return mixed array|bool
     */
    public function get($name)
    {
        $name = ucfirst($name);

        if ($this->check($name)) {
            return self::$_postTypes[$name];
        }
        return false;
    }

    /**
     * mapTableFields
     *
     * Maps the given table-fields and returns a mapped array with usable table-fields.
     *
     * @param array $fields The fields to map.
     * @return array
     */
    public function mapTableFields($fields)
    {
        $_fields = [];

        foreach ($fields as $key => $options) {
            $_options = $this->config('listFieldOptions');

            if (is_array($options)) {
                $_fields[$key] = array_merge($_options, $options);
            } else {
                $_fields[$options] = $_options;
            }
        }

        return $_fields;
    }

    /**
     * mapFormFields
     *
     * Maps the given form-fields and returns a mapped array with usable form-fields.
     * @param array $fields The fields to map.
     * @return array
     */
    public function mapFormFields($fields)
    {
        $_fields = [];

        foreach ($fields as $key => $options) {
            $_options = $this->config('formFieldOptions');

            if (is_array($options)) {
                $_fields[$key] = array_merge($_options, $options);
            } else {
                $_fields[$options] = $_options;
            }
        }

        return $_fields;
    }

    /**
     * _addMenu
     *
     * Gets the name of the PostType and adds a menu-item to the admin's main-menu.
     *
     * @param string $name Name of the PostType.
     * @param array $options Options of the PostType.
     * @return void
     */
    protected function _addMenu($name, $options)
    {
        $requstParams = $this->Controller->request->params;
        if (key_exists('prefix', $requstParams) && $requstParams['prefix'] == 'admin') {
            $this->Controller->Menu->add($options['alias'], [
                'url' => [
                    'prefix' => 'admin',
                    'plugin' => 'PostTypes',
                    'controller' => 'PostTypes',
                    'action' => 'index',
                    'type' => lcfirst($name),
                ]
            ]);
        }
    }

    /**
     * postTypeFinder
     *
     * Tries to get the posttype from the url.
     * Returns an empty string when its not found.
     *
     * @param \Cake\Network\Request $request The current request-object.
     * @return string
     */
    public function postTypeFinder($request)
    {
        if (key_exists('type', $request->query)) {
            return $request->query['type'];
        }
        if (key_exists(0, $request->params['pass'])) {
            return $request->params['pass'][0];
        }
        return '';
    }

    /**
     * _registerFromConfigure
     *
     * This method gets the types from the Configure: `PostTypes.register.*`.
     *
     * ### Adding Post-Types via the `Configure`-class
     * You can add an PostType by:
     *
     * `Configure::write('PostTypes.Register.MyType', [*settings*]);`
     *
     * @return void
     */
    protected function _registerFromConfigure()
    {
        $configure = Configure::read('PostTypes.Register');

        if (!is_array($configure)) {
            $configure = [];
        }

        foreach ($configure as $key => $item) {
            $this->register($key, $item);
        }
    }
}
