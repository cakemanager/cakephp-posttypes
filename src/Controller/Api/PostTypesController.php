<?php

namespace PostTypes\Controller\Api;

use PostTypes\Controller\AppController;

class PostTypesController extends AppController
{

    use \Crud\Controller\ControllerTrait;

    public function initialize() {
        parent::initialize();

        if(!$this->_apiAllowed()) {
            throw new \Exception("The API could not be loaded.");
        }

        $this->modelClass = $this->Settings['model'];

        $this->loadComponent('RequestHandler');
        $this->loadComponent('CakeManager.IsAuthorized');
        $this->loadComponent('Crud.Crud', [
            'actions'   => [
                'Crud.view',
                'Crud.edit',
                'Crud.index',
                'Crud.add',
                'Crud.delete'
            ],
            'listeners' => [
                'Crud.Api',
            ]
        ]);

        $this->Auth->allow([]);
    }

    public function isAuthorized($user) {

        $this->Authorizer->action('*', function($auth) {
            $auth->allowRole(1);
        });

        return $this->Authorizer->authorize();
    }

}
