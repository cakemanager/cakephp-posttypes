<?php

namespace PostTypes\Controller\Admin;

use PostTypes\Controller\AppController;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\Core\Configure;

/**
 * PostTypes Controller
 *
 * @property \PostTypes\Model\Table\PostTypesTable $PostTypes
 */
class PostTypesController extends AppController
{

    public $uses = [];

    public function isAuthorized($user) {

        $this->Authorizer->action('*', function($auth) {
            $auth->allowRole(1);
        });

        // setting up an event for the index
        $_event = new Event('Controller.PostTypes.isAuthorized.' . $this->_type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        return $this->Authorizer->authorize();
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($type = null) {

        // setting up an event for the index
        $_event = new Event('Controller.PostTypes.beforeIndex.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        $this->paginate = [
            'limit' => 25,
            'order' => [
                ucfirst($type) . '.id' => 'asc'
            ]
        ];

        $this->set('types', $this->paginate($this->Types));

        // setting up an event for the index
        $_event = new Event('Controller.PostTypes.afterIndex.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);


        if (!$this->Settings['views']['index']) {
            $this->render(Configure::read('PostTypes.AdminPostTypeViews.index'));
            return;
        }

        $this->render($this->Settings['views']['index']);
    }

    /**
     * View method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function view($type = null, $id = null) {

        // setting up an event for the view
        $_event = new Event('Controller.PostTypes.beforeView.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $type = $this->Types->get($id, [
            'contain' => $this->Settings['contain']
        ]);
        $this->set('type', $type);

        // setting up an event for the view
        $_event = new Event('Controller.PostTypes.afterView.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        if (!$this->Settings['views']['view']) {
            $this->render(Configure::read('PostTypes.AdminPostTypeViews.view'));
            return;
        }

        $this->render($this->Settings['views']['view']);
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add($type = null) {

        // setting up an event for the add
        $_event = new Event('Controller.PostTypes.beforeAdd.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        $type = $this->Types->newEntity();
        if ($this->request->is('post')) {
            $type = $this->Types->newEntity($this->request->data);
            if ($this->Types->save($type)) {
                $this->Flash->success('The post type has been saved.');
                return $this->redirect(['action' => 'index', 'type' => $this->type]);
            } else {
                $this->Flash->error('The post type could not be saved. Please, try again.');
            }
        }
        $this->set(compact('type'));

        // setting up an event for the add
        $_event = new Event('Controller.PostTypes.afterAdd.' . $type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        if (!$this->Settings['views']['view']) {
            $this->render(Configure::read('PostTypes.AdminPostTypeViews.view'));
            return;
        }

        $this->render($this->Settings['views']['view']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function edit($type = null, $id = null) {

        // setting up an event for the edit
        $_event = new Event('Controller.PostTypes.beforeEdit.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $type = $this->Types->get($id, [
            'contain' => $this->Settings['contain']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $type = $this->Types->patchEntity($type, $this->request->data, ['associated' => ['Metas']]);
            if ($this->Types->save($type, ['associated' => ['Metas']])) {
                $this->Flash->success('The post type has been saved.');
                return $this->redirect(['action' => 'index', 'type' => $this->type]);
            } else {
                $this->Flash->error('The type could not be saved. Please, try again.');
            }
        }
        $this->set(compact('type'));

        // setting up an event for the edit
        $_event = new Event('Controller.PostTypes.afterEdit.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        if (!$this->Settings['views']['view']) {
            $this->render(Configure::read('PostTypes.AdminPostTypeViews.view'));
            return;
        }

        $this->render($this->Settings['views']['view']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function delete($type = null, $id = null) {

        // setting up an event for the delete
        $_event = new Event('Controller.PostTypes.beforeDelete.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $postType = $this->Types->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Types->delete($postType)) {
            $this->Flash->success('The post type has been deleted.');
            return $this->redirect(['action' => 'index', 'type' => $this->type]);
        } else {
            $this->Flash->error('The post type could not be deleted. Please, try again.');
            return $this->redirect(['action' => 'index', 'type' => $this->type]);
        }

        // setting up an event for the delete
        $_event = new Event('Controller.PostTypes.afterDelete.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);
    }

}
