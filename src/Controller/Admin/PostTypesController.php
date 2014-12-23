<?php

namespace PostTypes\Controller\Admin;

use PostTypes\Controller\AppController;

/**
 * PostTypes Controller
 *
 * @property \PostTypes\Model\Table\PostTypesTable $PostTypes
 */
class PostTypesController extends AppController
{

    public $uses = [];

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);

        $type = $this->request->params['pass'][0];

        $check = $this->PostTypes->check($type);

        if (!$check) {
            throw new \Exception("The PostType is not known");
        }

        $this->Settings = $this->PostTypes->get($type);

        $this->Types = $this->loadModel($this->Settings['model']);

        if(!$this->Settings['fields']) {
            $this->Settings['fields'] = $this->doCallback('postTypeFields');
        }


        $this->doCallBack('beforeFilter');
    }

    protected function doCallback($method_name) {

        $check = method_exists($this->Types, $method_name);

        if ($check) {
            return call_user_method($method_name, $this->Types, $this);
        }
    }

    public function beforeRender(\Cake\Event\Event $event) {

        $this->set('postType', $this->Settings);

        parent::beforeRender($event);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($type) {

        $this->doCallback('beforeIndex');

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
    public function view($type, $id = null) {

        $this->doCallback('beforeView');

        $type = $this->Types->get($id, [
            'contain' => []
        ]);
        $this->set('type', $type);

        $this->doCallback('afterView');
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add($type) {
        $this->doCallback('beforeAdd');


        $type = $this->Types->newEntity($this->request->data);
        if ($this->request->is('post')) {
            if ($this->Types->save($type)) {
                $this->Flash->success('The post type has been saved.');
                return $this->redirect(['action' => 'index']);
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
    public function edit($type, $id = null) {

        $this->doCallback('beforeEdit');

        $type = $this->Types->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $type = $this->Types->patchEntity($type, $this->request->data);
            if ($this->Types->save($type)) {
                $this->Flash->success('The post type has been saved.');
                return $this->redirect(['action' => 'index']);
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
    public function delete($type, $id = null) {

        $this->doCallback('beforeDelete');

        $postType = $this->PostTypes->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->PostTypes->delete($postType)) {
            $this->Flash->success('The post type has been deleted.');
        } else {
            $this->Flash->error('The post type could not be deleted. Please, try again.');
        }

        $this->doCallback('afterDelete');

        return $this->redirect(['action' => 'index']);
    }

}
