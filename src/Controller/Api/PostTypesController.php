<?php

namespace PostTypes\Controller\Api;

use PostTypes\Controller\AppController;
use Cake\Event\Event;

class PostTypesController extends AppController
{

    use \Crud\Controller\ControllerTrait;

    public function initialize() {
        parent::initialize();

        if (!$this->_apiAllowed()) {
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

        $this->Crud->mapAction('list', 'Crud.Index');

        $this->Auth->allow([]);

         // setting up an event for the beforeFilter
        $_event = new Event('Controller.Api.PostTypes.beforeFilter.' . $this->_type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);
    }

    public function isAuthorized($user) {

        $this->Authorizer->action('*', function($auth) {
            $auth->allowRole(1);
        });

         // setting up an event for the index
        $_event = new Event('Controller.Api.PostTypes.isAuthorized.' . $this->_type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        return $this->Authorizer->authorize();
    }

    public function index($type) {

        // setting up an event for the index
        $_event = new Event('Controller.Api.PostTypes.beforeIndex.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        $this->Crud->execute('index');

        // setting up an event for the index
        $_event = new Event('Controller.Api.PostTypes.afterIndex.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);
    }

    public function add($type) {

        // setting up an event for the add
        $_event = new Event('Controller.Api.PostTypes.beforeAdd.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        $this->Crud->execute('index');

        // setting up an event for the add
        $_event = new Event('Controller.Api.PostTypes.afterAdd.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);
    }

    public function view($type, $id = null) {

        // setting up an event for the view
        $_event = new Event('Controller.Api.PostTypes.beforeView.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $this->Crud->execute('view', $id);

        // setting up an event for the view
        $_event = new Event('Controller.Api.PostTypes.afterView.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);
    }

    public function edit($type, $id = null) {

        // setting up an event for the edit
        $_event = new Event('Controller.Api.PostTypes.beforeEdit.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $this->Crud->execute('edit', $id);

        // setting up an event for the edit
        $_event = new Event('Controller.Api.PostTypes.afterEdit.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);
    }

    public function delete($type, $id = null) {

        // setting up an event for the delete
        $_event = new Event('Controller.Api.PostTypes.beforeDelete.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $this->Crud->execute('delete', $id);

        // setting up an event for the delete
        $_event = new Event('Controller.Api.PostTypes.afterDelete.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);
    }

}
