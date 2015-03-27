<?php
namespace PostTypes\Test\TestCase\Shell;

use Cake\TestSuite\TestCase;
use PostTypes\Shell\RegisterShell;

/**
 * PostTypes\Shell\RegisterShell Test Case
 */
class RegisterShellTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMock('Cake\Console\ConsoleIo');
        $this->Register = new RegisterShell($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Register);

        parent::tearDown();
    }

    /**
     * Test main method
     *
     * @return void
     */
    public function testMain()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
