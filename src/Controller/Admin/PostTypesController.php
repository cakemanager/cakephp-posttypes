<?php

namespace PostTypes\Controller\Admin;

use PostTypes\Controller\AppController;
use Cake\Routing\Router;

/**
 * PostTypes Controller
 *
 * @property \PostTypes\Model\Table\PostTypesTable $PostTypes
 */
class PostTypesController extends AppController
{

    public $uses = [];

    /**
     * Index method
     *
     * @return void
     */
    public function index($posttype = null) {

        $this->doCallback('beforeIndex');

        $this->paginate = [
            'limit' => 25,
            'order' => [
                'Bookmarks.id' => 'asc'
            ]
        ];

        $this->set('types', $this->paginate($this->Types));

        $this->doCallback('afterIndex');
    }

    /**
     * View method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function view($posttype = null, $id = null) {

        $this->doCallback('beforeView');

        $type = $this->Types->get($id, [
            'contain' => $this->Settings['contain']
        ]);
        $this->set('type', $type);

        $this->doCallback('afterView');
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add($posttype = null) {

        $this->doCallback('beforeAdd');

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

        $this->doCallback('afterAdd');
    }

    /**
     * Edit method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function edit($posttype = null, $id = null) {

        $this->doCallback('beforeEdit');

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

        $this->doCallback('afterEdit');
    }

    /**
     * Delete method
     *
     * @param string|null $id Post Type id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function delete($posttype = null, $id = null) {

        $this->doCallback('beforeDelete');

        $postType = $this->Types->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Types->delete($postType)) {
            $this->Flash->success('The post type has been deleted.');
            return $this->redirect(['action' => 'index', 'type' => $this->type]);
        } else {
            $this->Flash->error('The post type could not be deleted. Please, try again.');
            return $this->redirect(['action' => 'index', 'type' => $this->type]);
        }

        $this->doCallback('afterDelete');
    }

}
