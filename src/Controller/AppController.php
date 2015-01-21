<?php

namespace PostTypes\Controller;

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
    public $type = null;

    /**
     * BeforeFilter Event
     *
     * @param \Cake\Event\Event $event
     * @throws \Exception
     */
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);

        // get the type-string
        $this->type = $this->PostTypes->postTypeFinder($this->request);

        // check if the string exists
        $check = $this->PostTypes->check($this->type);

        if (!$check) {
            throw new \Exception("The PostType is not known");
        }

        // nothing happened so lets get the settings
        $this->Settings = $this->PostTypes->get($this->type);

        // lets initialize the model too
        $this->Types = $this->loadModel($this->Settings['model']);

        // get the fieldlist from the model if not set
        if (!$this->Settings['formFields']) {
            $this->Settings['formFields'] = $this->PostTypes->mapFormFields($this->doCallback('postTypeFormFields'));
        }

        // get the fieldlist from the model if not set
        if (!$this->Settings['tableFields']) {
            $this->Settings['tableFields'] = $this->PostTypes->maptableFields($this->doCallback('postTypetableFields'));
        }

        // setting up the authorized-configuration
        $this->IsAuthorized->config('model', 'Types');

        $this->IsAuthorized->config('param', 1);

        // first callback: beforeFilter
        $this->doCallBack('beforeFilter');
    }

    /**
     * BeforeRender Event
     *
     * @param \Cake\Event\Event $event
     */
    public function beforeRender(\Cake\Event\Event $event) {

        $this->set('postType', $this->Settings);

        parent::beforeRender($event);
    }

    public function isAuthorized($user) {

        $this->Authorizer->action('*', function($auth) {
            $auth->allowRole(null);
        });

        $this->Authorizer->action(['index', 'view'], function($auth) {
            $auth->allowRole([1]);
        });

        $this->Authorizer->action(['edit'], function($auth) {
            $auth->setRole([1], $this->IsAuthorized->authorize());
        });

        return $this->Authorizer->authorize();
    }

    /**
     * This method fires the given callback to the current model if it's set
     *
     * @param string $method_name
     * @return mixed from the callback
     */
    protected function doCallback($callback_name) {

        $method_name = Hash::get($this->Settings, 'callbacks.' . $callback_name);

        $check = method_exists($this->Types, $method_name);

        if ($check) {
            return call_user_method($method_name, $this->Types, $this);
        }
    }

}
