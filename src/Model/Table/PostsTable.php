<?php
namespace PostTypes\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Posts Model
 */
class PostsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('posts');
		$this->displayField('title');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('ParentPosts', [
			'alias' => 'ParentPosts',
			'className' => 'PostTypes.Posts',
			'foreignKey' => 'parent_id'
		]);
		$this->hasMany('ChildPosts', [
			'alias' => 'ChildPosts',
			'className' => 'PostTypes.Posts',
			'foreignKey' => 'parent_id'
		]);
	}

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator instance
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('id', 'create')
			->allowEmpty('title')
			->allowEmpty('slug')
			->allowEmpty('type')
			->allowEmpty('content')
			->allowEmpty('status')
			->add('published', 'valid', ['rule' => 'numeric'])
			->requirePresence('published', 'create')
			->notEmpty('published')
			->add('parent_id', 'valid', ['rule' => 'numeric'])
			->requirePresence('parent_id', 'create')
			->notEmpty('parent_id')
			->add('created_by', 'valid', ['rule' => 'numeric'])
			->allowEmpty('created_by')
			->add('modified_by', 'valid', ['rule' => 'numeric'])
			->allowEmpty('modified_by');

		return $validator;
	}

}
