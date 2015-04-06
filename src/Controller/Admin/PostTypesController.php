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
namespace PostTypes\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use PostTypes\Controller\AppController;

/**
 * PostTypes Controller
 *
 */
class PostTypesController extends AppController
{
    /**
     * Model that will be used (so none).
     * @var array
     */
    public $uses = [];

    /**
     * initialize
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Utils.Search');

        $this->helpers['Utils.Search'] = [];
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
            $auth->allowRole(1);
        });

        // setting up an event for the index
        $_event = new Event('Controller.PostTypes.isAuthorized.' . $this->_type, $this, [
        ]);
        $this->eventManager()->dispatch($_event);

        return $this->Authorizer->authorize();
    }

    /**
     * beforeRender event
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->set('title', $this->Settings['alias']);
    }

    /**
     * Index method
     *
     * @param string $type The requested PostType.
     * @return void
     */
    public function index($type = null)
    {
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

        foreach ($this->Settings['filters'] as $key => $value) {
            if (is_array($value)) {
                $this->Search->addFilter($key, $value);
            } else {
                $this->Search->addFilter($value);
            }
        }

        $query = $this->Search->search($this->Model->find('all'));

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
     * @param string $_type The requested PostType.
     * @param string|null $id Post Type id
     * @return void
     */
    public function view($_type = null, $id = null)
    {
        // setting up an event for the view
        $_event = new Event('Controller.PostTypes.beforeView.' . $_type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $type = $this->Model->get($id, [
            'contain' => $this->Settings['contain']
        ]);
        $this->set('type', $type);

        // setting up an event for the view
        $_event = new Event('Controller.PostTypes.afterView.' . $_type, $this, [
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
     * @param string $_type The requested PostType.
     * @return void
     */
    public function add($_type = null)
    {
        if (empty($this->Settings['formFields'])) {
            $columns = $this->Model->schema()->columns();
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

        $type = $this->Model->newEntity();
        if ($this->request->is('post')) {
            $type = $this->Model->newEntity($this->request->data);
            if ($this->Model->save($type)) {
                $this->Flash->success(__('The {0} has been saved.', [$this->Settings['type']]));
                return $this->redirect(['action' => 'index', 'type' => $_type]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', [$this->Settings['type']]));
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
     * @param string $_type The requested PostType.
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function edit($_type = null, $id = null)
    {
        // setting up an event for the edit
        $_event = new Event('Controller.PostTypes.beforeEdit.' . $_type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $type = $this->Model->get($id, [
            'contain' => $this->Settings['contain']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $type = $this->Model->patchEntity($type, $this->request->data);
            if ($this->Model->save($type)) {
                $this->Flash->success(__('The {0} has been saved.', [$this->Settings['type']]));
                return $this->redirect(['action' => 'index', 'type' => $_type]);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', [$this->Settings['type']]));
            }
        }
        $this->set(compact('type'));

        // setting up an event for the edit
        $_event = new Event('Controller.PostTypes.afterEdit.' . $_type, $this, [
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
     * @param string $_type The requested PostType.
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function delete($_type = null, $id = null)
    {
        // setting up an event for the delete
        $_event = new Event('Controller.PostTypes.beforeDelete.' . $_type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);

        $postType = $this->Model->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Model->delete($postType)) {
            $this->Flash->success(__('The {0} has been deleted.', [$this->Settings['type']]));
            return $this->redirect(['action' => 'index', 'type' => $_type]);
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', [$this->Settings['type']]));
            return $this->redirect(['action' => 'index', 'type' => $_type]);
        }

        // setting up an event for the delete
        $_event = new Event('Controller.PostTypes.afterDelete.' . $_type, $this, [
            'id' => $id,
        ]);
        $this->eventManager()->dispatch($_event);
    }
}
