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
namespace PostTypes\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\TestCase;
use PostTypes\Controller\Component\PostTypesComponent;

/**
 * PostTypes\Controller\Component\PostTypesComponent Test Case
 */
class PostTypesComponentTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Setup our component and fake test controller
        $params = [
            'prefix' => 'admin',
            'plugin' => 'cakemanager',
            'controller' => 'users',
            'action' => 'index'
        ];

        $request = new Request(['params' => $params]);
        $response = new Response();

        $this->Controller = $this->getMock('Cake\Controller\Controller', ['redirect', 'initPostTypes'], [$request, $response]);

        $this->Controller->loadComponent('CakeManager.Manager');

        $registry = new ComponentRegistry($this->Controller);

        $this->PostTypes = new PostTypesComponent($registry);

        $this->PostTypes->setController($this->Controller);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $this->PostTypes->remove();

        unset($this->PostTypes);

        parent::tearDown();
    }

    /**
     * testSetController
     *
     * @return void
     */
    public function testSetController()
    {
        $this->assertNotEmpty($this->PostTypes->Controller);

        $this->PostTypes->setController('fake');

        $this->assertEquals('fake', $this->PostTypes->Controller);
    }

    /**
     * testBeforeFilter
     *
     * @return void
     */
    public function testBeforeFilter()
    {
        $this->Controller->expects($this->once())->method('initPostTypes');

        $event = new Event('Component.beforeFilter', $this->Controller);

        $this->PostTypes->beforeFilter($event);
    }

    /**
     * testBeforeFilterNoPrefix
     *
     * @return void
     */
    public function testBeforeFilterNoPrefix()
    {
        // Setup our component and fake test controller
        $params = [
            'prefix' => false,
            'plugin' => 'cakemanager',
            'controller' => 'users',
            'action' => 'index'
        ];

        $request = new Request(['params' => $params]);
        $response = new Response();

        $testController = $this->getMock('Cake\Controller\Controller', ['redirect', 'initPostTypes'], [$request, $response]);

        $testController->loadComponent('CakeManager.Manager');

        $this->PostTypes->setController($testController);

        $testController->expects($this->never())->method('initPostTypes');

        $event = new Event('Component.beforeFilter', $testController);

        $this->PostTypes->beforeFilter($event);
    }

    /**
     * testRegisterDefault
     *
     * @return void
     */
    public function testRegisterDefault()
    {
        $this->assertEmpty($this->PostTypes->get());

        $this->PostTypes->register('Blogs');

        $this->assertNotEmpty($this->PostTypes->get());

        $expected = [
            'menu' => false,
            'model' => 'Blogs',
            'contain' => [],
            'tableFields' => [],
            'formFields' => [],
            'alias' => 'Blogs',
            'name' => 'Blogs',
            'type' => 'Blog',
            'filters' => [],
            'actions' => [
                'view' => true,
                'edit' => true,
                'delete' => true,
                'add' => true
            ],
            'views' => [
                'index' => false,
                'view' => false,
                'add' => false,
                'edit' => false
            ]
        ];

        $this->assertEquals($expected, $this->PostTypes->get('Blogs'));
    }

    /**
     * testRegisterCustom
     *
     * @return void
     */
    public function testRegisterCustom()
    {
        $this->assertEmpty($this->PostTypes->get());

        $this->PostTypes->register('Blogs', [
            'menu' => true,
            'model' => 'CustomBlogs',
            'contain' => ['Users'],
            'tableFields' => [
                'id',
                'title',
                'user_id',
                'created',
                'modified'
            ],
            'formFields' => [
                'id',
                'title' => [
                    'placeholder' => 'custom title'
                ],
                'body',
                'user_id'
            ],
            'alias' => 'CustomAlias',
            'filters' => [
                'title'
            ],
            'actions' => [
                'view' => false,
                'edit' => true,
                'delete' => true,
                'add' => true
            ],
            'views' => [
                'index' => 'Custom/Template/Blogs/CustomIndex.ctp',
                'view' => 'Custom/Template/Blogs/CustomIndex.ctp',
                'add' => 'Custom/Template/Blogs/CustomIndex.ctp',
                'edit' => 'Custom/Template/Blogs/CustomIndex.ctp'
            ]
        ]);

        $this->assertNotEmpty($this->PostTypes->get());

        $expected = [
            'menu' => true,
            'model' => 'CustomBlogs',
            'contain' => [
                0 => 'Users'
            ],
            'tableFields' => [
                'id' => [
                    'hide' => false,
                    'get' => false,
                    'before' => '',
                    'after' => ''
                ],
                'title' => [
                    'hide' => false,
                    'get' => false,
                    'before' => '',
                    'after' => ''
                ],
                'user_id' => [
                    'hide' => false,
                    'get' => false,
                    'before' => '',
                    'after' => ''
                ],
                'created' => [
                    'hide' => false,
                    'get' => false,
                    'before' => '',
                    'after' => ''
                ],
                'modified' => [
                    'hide' => false,
                    'get' => false,
                    'before' => '',
                    'after' => ''
                ]
            ],
            'formFields' => [
                'id' => [],
                'title' => [
                    'placeholder' => 'custom title'
                ],
                'body' => [],
                'user_id' => []
            ],
            'alias' => 'CustomAlias',
            'name' => 'Blogs',
            'type' => 'Blog',
            'filters' => [
                0 => 'title'
            ],
            'actions' => [
                'view' => false,
                'edit' => true,
                'delete' => true,
                'add' => true
            ],
            'views' => [
                'index' => 'Custom/Template/Blogs/CustomIndex.ctp',
                'view' => 'Custom/Template/Blogs/CustomIndex.ctp',
                'add' => 'Custom/Template/Blogs/CustomIndex.ctp',
                'edit' => 'Custom/Template/Blogs/CustomIndex.ctp'
            ]
        ];

        $this->assertEquals($expected, $this->PostTypes->get('Blogs'));
    }

    /**
     * testRemove
     *
     * @return void
     */
    public function testRemove()
    {
        $this->assertEmpty($this->PostTypes->get());

        $this->PostTypes->register('Blogs');
        $this->PostTypes->register('Articles');

        $this->assertNotEmpty($this->PostTypes->get());

        $this->PostTypes->remove('Blogs');

        $this->assertArrayHasKey('Articles', $this->PostTypes->get());
        $this->assertArrayNotHasKey('Blogs', $this->PostTypes->get());

        $this->PostTypes->remove('Articles');

        $this->assertArrayNotHasKey('Blogs', $this->PostTypes->get());
        $this->assertArrayNotHasKey('Articles', $this->PostTypes->get());

        $this->assertEmpty($this->PostTypes->get());

        $this->PostTypes->register('Blogs');
        $this->PostTypes->register('Articles');

        $this->assertNotEmpty($this->PostTypes->get());

        $this->PostTypes->remove();

        $this->assertEmpty($this->PostTypes->get());
    }

    /**
     * testCheck
     *
     * @return void
     */
    public function testCheck()
    {
        $this->assertEmpty($this->PostTypes->get());

        $this->PostTypes->register('Blogs');
        $this->PostTypes->register('Articles');

        $this->assertTrue($this->PostTypes->check('Blogs'));
        $this->assertTrue($this->PostTypes->check('Articles'));
        $this->assertFalse($this->PostTypes->check('Custom'));

        $this->PostTypes->register('Custom');

        $this->assertTrue($this->PostTypes->check('Custom'));
    }

    /**
     * testMapTableFieldsDefault
     *
     * @return void
     */
    public function testMapTableFieldsDefault()
    {
        $fields = [
            'id',
            'title',
            'created',
            'modified'
        ];

        $expected = [
            'id' => [
                'hide' => false,
                'get' => false,
                'before' => '',
                'after' => ''
            ],
            'title' => [
                'hide' => false,
                'get' => false,
                'before' => '',
                'after' => ''
            ],
            'created' => [
                'hide' => false,
                'get' => false,
                'before' => '',
                'after' => ''
            ],
            'modified' => [
                'hide' => false,
                'get' => false,
                'before' => '',
                'after' => ''
            ]
        ];

        $this->assertEquals($expected, $this->PostTypes->mapTableFields($fields));
    }

    /**
     * testMapTableFieldsCustom
     *
     * @return void
     */
    public function testMapTableFieldsCustom()
    {
        $fields = [
            'id',
            'title' => [
                'hide' => true,
            ],
            'user_id' => [
                'get' => 'user.email'
            ],
            'created' => [
                'before' => '<b>',
                'after' => '</b>',
            ],
            'modified'
        ];

        $expected = [
            'id' => [
                'hide' => false,
                'get' => false,
                'before' => '',
                'after' => ''
            ],
            'title' => [
                'hide' => true,
                'get' => false,
                'before' => '',
                'after' => ''
            ],
            'user_id' => [
                'hide' => false,
                'get' => 'user.email',
                'before' => '',
                'after' => ''
            ],
            'created' => [
                'hide' => false,
                'get' => false,
                'before' => '<b>',
                'after' => '</b>'
            ],
            'modified' => [
                'hide' => false,
                'get' => false,
                'before' => '',
                'after' => ''
            ]
        ];

        $this->assertEquals($expected, $this->PostTypes->mapTableFields($fields));
    }

    /**
     * testMapFormFields
     *
     * @return void
     */
    public function testMapFormFields()
    {
        $fields = [
            'id',
            'title',
            'created',
            'modified'
        ];

        $expected = [
            'id' => [],
            'title' => [],
            'created' => [],
            'modified' => []
        ];

        $this->assertEquals($expected, $this->PostTypes->mapFormFields($fields));
    }

    /**
     * testPostTypeFinder
     *
     * @return void
     */
    public function testPostTypeFinder()
    {
        $request = new Request();

        $this->assertEmpty($this->PostTypes->postTypeFinder($request));

        $query = [
            'type' => 'Blogs'
        ];
        $request = new Request(['query' => $query]);

        $this->assertEquals('Blogs', $this->PostTypes->postTypeFinder($request));

        $params = [
            'pass' => [
                0 => 'Articles'
            ]
        ];
        $request = new Request(['params' => $params]);

        $this->assertEquals('Articles', $this->PostTypes->postTypeFinder($request));
    }
}
