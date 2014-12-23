<?php
namespace PostTypes\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity.
 */
class Post extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'title' => true,
		'slug' => true,
		'type' => true,
		'content' => true,
		'status' => true,
		'published' => true,
		'parent_id' => true,
		'created_by' => true,
		'modified_by' => true,
		'parent_post' => true,
		'child_posts' => true,
	];

}
