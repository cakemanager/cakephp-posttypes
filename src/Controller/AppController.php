<?php namespace PostTypes\Controller;

use App\Controller\AppController as BaseController;
use Cake\Utility\Hash;

class AppController extends BaseController
{
    /**
     * The current type table
     *
     * @var type
     */
    public $Types = null;

    /**
     * The current type-name (string)
     * @var string
     */
    protected $_type = null;

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
        $this->Types = $this->loadModel($this->Settings['model']);
    }

    /**
     * BeforeFilter Event
     *
     * @param \Cake\Event\Event $event
     * @throws \Exception
     */
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * BeforeRender Event
     *
     * @param \Cake\Event\Event $event
     */
    public function beforeRender(\Cake\Event\Event $event)
    {
        parent::beforeRender($event);

        $this->set('postType', $this->Settings);
    }

    public function isAuthorized($user)
    {

        $this->Authorizer->action('*', function($auth) {
            $auth->allowRole([1]);
        });

        return $this->Authorizer->authorize();
    }

    /**
     * Checks if an api is allowed
     *
     * @return type
     */
    protected function _apiAllowed()
    {

        return $this->Settings['api'];
    }
}
