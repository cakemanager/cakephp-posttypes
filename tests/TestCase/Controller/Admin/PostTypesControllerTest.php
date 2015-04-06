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
namespace PostTypes\Test\TestCase\Controller\Admin;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * PostTypes\Controller\PostTypesController Test Case
 */
class PostTypesControllerTest extends IntegrationTestCase
{
    /**
     * fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.post_types.blogs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * testAuthorization
     *
     * @return void
     */
    public function testAuthorization()
    {
        // index
        $this->get('/admin/posttypes/blogs/index');
        $this->assertRedirect('/users/login');

        // add
        $this->get('/admin/posttypes/blogs/add');
        $this->assertRedirect('/users/login');

        // view
        $this->get('/admin/posttypes/blogs/view');
        $this->assertRedirect('/users/login');

        // edit
        $this->get('/admin/posttypes/blogs/edit');
        $this->assertRedirect('/users/login');

        // delete
        $this->get('/admin/posttypes/blogs/delete');
        $this->assertRedirect('/users/login');

        // setting a wrong role_id
        $this->session(['Auth' => ['User' => ['role_id' => 2]]]);

        // index
        $this->get('/admin/posttypes/blogs/index');
        $this->assertResponseError();

        // add
        $this->get('/admin/posttypes/blogs/add');
        $this->assertResponseError();

        // view
        $this->get('/admin/posttypes/blogs/view');
        $this->assertResponseError();

        // edit
        $this->get('/admin/posttypes/blogs/edit');
        $this->assertResponseError();

        // new password
        $this->get('/admin/posttypes/blogs/newPassword');
        $this->assertResponseError();

        // delete
        $this->get('/admin/posttypes/blogs/delete');
        $this->assertResponseError();
    }

    /**
     * testIndex
     *
     * @return void
     */
    public function testIndex()
    {
        $this->session(['Auth.User' => [
                'id' => 1,
                'role_id' => 1,
                'email' => 'bob@cakemanager.org'
        ]]);

        $this->get('/admin/posttypes/blogs/index');

        $this->assertResponseOk();

        $this->assertEquals('Blogs', $this->viewVariable('title'));
        $this->assertNotNull($this->viewVariable('postType'));
        $this->assertNotNull($this->viewVariable('types'));
        $this->assertNotNull($this->viewVariable('menu'));
    }

    /**
     * testView
     *
     * @return void
     */
    public function testView()
    {
        $this->session(['Auth.User' => [
                'id' => 1,
                'role_id' => 1,
                'email' => 'bob@cakemanager.org'
        ]]);

        $this->get('/admin/posttypes/blogs/view/1');

        $this->assertResponseOk();

        $this->assertEquals('Blogs', $this->viewVariable('title'));
        $this->assertNotNull($this->viewVariable('postType'));
        $this->assertNotNull($this->viewVariable('type'));
        $this->assertNotNull($this->viewVariable('menu'));
    }

    /**
     * testAdd
     *
     * @return void
     */
    public function testAdd()
    {
        $this->session(['Auth.User' => [
                'id' => 1,
                'role_id' => 1,
                'email' => 'bob@cakemanager.org'
        ]]);

        $this->get('/admin/posttypes/blogs/add');

        $this->assertResponseOk();

        $this->assertEquals('Blogs', $this->viewVariable('title'));
        $this->assertNotNull($this->viewVariable('postType'));
        $this->assertInstanceOf('App\Model\Entity\Blog', $this->viewVariable('type'));
        $this->assertNotNull($this->viewVariable('menu'));

        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/admin/posttypes/blogs/add">');
        $this->assertResponseContains('<input type="hidden" name="id" id="id">');
        $this->assertResponseContains('<input type="text" name="title" maxlength="256" id="title">');
        $this->assertResponseContains('<textarea name="content" id="content" rows="5"></textarea>');
        $this->assertResponseContains('<button type="submit">Submit</button>');
        $this->assertResponseContains('</form>');
    }

    /**
     * testAddPost
     *
     * @return void
     */
    public function testAddPost()
    {
        $this->session(['Auth.User' => [
                'id' => 1,
                'role_id' => 1,
                'email' => 'bob@cakemanager.org'
        ]]);

        $blogs = TableRegistry::get('Blogs');

        $this->assertEquals(10, $blogs->find('all')->count());

        $data = [
            'title' => 'My first title',
            'content' => 'My first content',
            'user_id' => 1,
        ];

        $this->post('/admin/posttypes/blogs/add', $data);

        $this->assertResponseSuccess();

        $this->assertRedirect('/admin/posttypes/blogs/index');

        $this->assertEquals(11, $blogs->find('all')->count());

        $blog = $blogs->get(11);

        $this->assertEquals('My first title', $blog->title);
        $this->assertEquals('My first content', $blog->content);
        $this->assertEquals(1, $blog->user_id);
    }

    /**
     * testEdit
     *
     * @return void
     */
    public function testEdit()
    {
        $this->session(['Auth.User' => [
                'id' => 1,
                'role_id' => 1,
                'email' => 'bob@cakemanager.org'
        ]]);

        $this->get('/admin/posttypes/blogs/edit/1');

        $this->assertResponseOk();

        $this->assertEquals('Blogs', $this->viewVariable('title'));
        $this->assertNotNull($this->viewVariable('postType'));
        $this->assertInstanceOf('App\Model\Entity\Blog', $this->viewVariable('type'));
        $this->assertNotNull($this->viewVariable('menu'));

        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/admin/posttypes/blogs/edit/1">');
        $this->assertResponseContains('<input type="hidden" name="id" id="id" value="1">');
        $this->assertResponseContains('<input type="text" name="title" maxlength="256" id="title" value="Lorem ipsum dolor sit amet">');
        $this->assertResponseContains('<textarea name="content" id="content" rows="5">Lorem ipsum');
        $this->assertResponseContains('<button type="submit">Submit</button>');
        $this->assertResponseContains('</form>');
    }

    /**
     * testEditPost
     *
     * @return void
     */
    public function testEditPost()
    {
        $this->session(['Auth.User' => [
                'id' => 1,
                'role_id' => 1,
                'email' => 'bob@cakemanager.org'
        ]]);

        $blogs = TableRegistry::get('Blogs');

        $blog = $blogs->get(1);

        $this->assertEquals('Lorem ipsum dolor sit amet', $blog->title);
        $this->assertContains('Lorem ipsum', $blog->content);
        $this->assertEquals(1, $blog->user_id);

        $data = [
            'title' => 'My modified title',
            'content' => 'My modified content',
        ];

        $this->post('/admin/posttypes/blogs/edit/1', $data);

        $this->assertResponseSuccess();

        $this->assertRedirect('/admin/posttypes/blogs/index');

        $blog = $blogs->get(1);

        $this->assertEquals('My modified title', $blog->title);
        $this->assertEquals('My modified content', $blog->content);
        $this->assertEquals(1, $blog->user_id);
    }

    /**
     * testDelete
     *
     * @return void
     */
    public function testDelete()
    {
        $this->session(['Auth.User' => [
                'id' => 1,
                'role_id' => 1,
                'email' => 'bob@cakemanager.org'
        ]]);

        $blogs = TableRegistry::get('Blogs');
        
        $this->assertEquals(10, $blogs->find('all')->count());
        
        $this->delete('/admin/posttypes/blogs/delete/1');
        
        $this->assertResponseSuccess();
        
        $this->assertRedirect('/admin/posttypes/blogs/index');
        
        $this->assertEquals(9, $blogs->find('all')->count());
    }
}
