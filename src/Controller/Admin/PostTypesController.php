<?php namespace PostTypes\Controller\Admin;

use PostTypes\Controller\AppController;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Utility\Hash;

/**
 * PostTypes Controller
 *
 * @property \PostTypes\Model\Table\PostTypesTable $PostTypes
 */
class PostTypesController extends AppController
{
    public $uses = [];

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Utils.Search');

        $this->helpers['Utils.Search'] = [];
    }

    public function isAuthorized($user)
    {
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
    public function index($type = null)
    {
        debug($this->Types->schema()->columns());

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

        foreach ($this->Settings['filters'] as $filter) {
            $this->Search->addFilter($filter);
        }

        $query = $this->Search->search($this->Types->find('all'));

        $this->set('types', $this->paginate($query));

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
    public function view($type = null, $id = null)
    {

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
    public function add($_type = null)
    {
        if (empty($this->Settings['formFields'])) {
            $columns = $this->Types->schema()->columns();
            $filter = Configure::read('PostTypes.FilteredColumns');
            foreach ($columns as $column) {
                if (!in_array($column, $filter)) {
                    $this->Settings['formFields'][$column] = [];
                }
            }
        }

        // setting up an event for the add
        $_event = new Event('Controller.PostTypes.beforeAdd.' . $_type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        $type = $this->Types->newEntity();
        if ($this->request->is('post')) {
            $type = $this->Types->newEntity($this->request->data);
            if ($this->Types->save($type)) {
                $this->Flash->success('The post type has been saved.');
                return $this->redirect(['action' => 'index', 'type' => $_type]);
            } else {
                $this->Flash->error('The post type could not be saved. Please, try again.');
            }
        }
        $this->set(compact('type'));

        // setting up an event for the add
        $_event = new Event('Controller.PostTypes.afterAdd.' . $_type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        if (!$this->Settings['views']['add']) {
            $this->render(Configure::read('PostTypes.AdminPostTypeViews.add'));
            return;
        }

        $this->render($this->Settings['views']['add']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function edit($type = null, $id = null)
    {

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

        if (!$this->Settings['views']['edit']) {
            $this->render(Configure::read('PostTypes.AdminPostTypeViews.edit'));
            return;
        }

        $this->render($this->Settings['views']['edit']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function delete($type = null, $id = null)
    {

        // setting up an event for the delete
        $_event = new Event('Controller.PostTypes.beforeDelete.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $postType = $this->Types->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Types->delete($postType)) {
            $this->Flash->success('The post type has been deleted.');
            return $this->redirect(['action' => 'index', 'type' => $type]);
        } else {
            $this->Flash->error('The post type could not be deleted. Please, try again.');
            return $this->redirect(['action' => 'index', 'type' => $type]);
        }

        // setting up an event for the delete
        $_event = new Event('Controller.PostTypes.afterDelete.' . $type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);
    }
}
