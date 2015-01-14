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

        // first callback: beforeFilter
        $this->doCallBack('beforeFilter');
    }

    /**
     * This method fires the given callback to the current model if it's set
     *
     * @param string $method_name
     * @return mixed from the callback
     */
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
    public function index($posttype = null) {

        $this->doCallback('beforeIndex');

        $this->set('types', $this->paginate($this->Types, [
                    'contain' => $this->Settings['contain']
        ]));

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

        $type = $this->Types->newEntity($this->request->data);
        if ($this->request->is('post')) {
            debug($this->request->data);
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
            $type = $this->Types->patchEntity($type, $this->request->data);
            if ($this->Types->save($type)) {
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
