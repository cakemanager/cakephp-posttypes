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
namespace PostTypes\Controller;

use App\Controller\AppController as BaseController;

/**
 * AppController for PostTypes Plugin
 *
 */
class AppController extends BaseController
{
    /**
     * The current type table
     *
     * @var \Cake\ORM\Table
     */
    public $Model = null;

    /**
     * The current type-name (string)
     * @var string
     */
    protected $_type = null;

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        // get the type-string
        $type = $this->PostTypes->postTypeFinder($this->request);
        $this->_type = $type;

        // check if the string exists. Will throw an exception
        $this->PostTypes->check($type, ['exception' => true]);

        // intialize the settings
        $this->Settings = $this->PostTypes->get($type);

        // lets initialize the model too
        $this->Model = $this->loadModel($this->Settings['model']);
    }

    /**
     * beforeFilter
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * beforeRender
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRender(\Cake\Event\Event $event)
    {
        parent::beforeRender($event);

        $this->set('postType', $this->Settings);
    }

    /**
     * isAuthorized
     *
     * @param array $user User.
     * @return bool
     */
    public function isAuthorized($user)
    {
        $this->Authorizer->action('*', function ($auth) {
            $auth->allowRole([1]);
        });

        return $this->Authorizer->authorize();
    }
}
