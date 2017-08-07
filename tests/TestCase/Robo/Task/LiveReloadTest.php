<?php
/**
 * Copyright (c) Yves Piquel (http://www.havokinspiration.fr)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Yves Piquel (http://www.havokinspiration.fr)
 * @link          http://github.com/elephfront/robo-css-minify
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Elephfront\RoboLiveReload\Tests;

use Elephfront\RoboLiveReload\Task\LiveReload;
use Elephfront\RoboLiveReload\Tests\Utility\MemoryLogger;
use PHPUnit\Framework\TestCase;
use Robo\Robo;

/**
 * Class LiveReloadTest
 *
 * Test cases for the LiveReload Robo task.
 */
class LiveReloadTest extends TestCase
{

    /**
     * Instance of the task that will be tested.
     *
     * @var \Elephfront\RoboLiveReload\Task\LiveReload
     */
    protected $task;

    /**
     * setUp.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Robo::setContainer(Robo::createDefaultContainer());
        $this->task = new LiveReload();
        $this->task->setLogger(new MemoryLogger());
    }

    /**
     * tearDown.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->task);
    }

    /**
     * Tests running the task will start the server and create the livereload js file to the proper location.
     * 
     * @return void
     */
    public function testRun()
    {
        $this->task
            ->jsPath('tests/app/assets/livereload/livereload.js')
            ->run();
        
        $this->assertTrue(file_exists('tests/app/assets/livereload/livereload.js'));
        $expected = <<<TEXT
var exampleSocket = new WebSocket('ws://127.0.0.1:22222/');
exampleSocket.onmessage = function(event) {if (event.data === 'reload') {location.reload();}};
TEXT;
        $this->assertEquals($expected, file_get_contents('tests/app/assets/livereload/livereload.js'));
        
        passthru('ps aux | grep "php" > tests/app/psaux');
        $processes = file_get_contents('tests/app/psaux');
        $this->assertNotFalse(strpos($processes, 'vendor/bin/elephfront-robo-live-reload 127.0.0.1 22222'));
    }

    /**
     * Tests running the task with a custom port and host will propagate those parameters to the js file and the
     * command executed.
     * 
     * @return void
     */
    public function testRunWithHostAndPort()
    {
        $this->task
            ->port(25852)
            ->host('localhost')
            ->jsPath('tests/app/assets/livereload/livereload.js')
            ->run();
        
        $this->assertTrue(file_exists('tests/app/assets/livereload/livereload.js'));
        $expected = <<<TEXT
var exampleSocket = new WebSocket('ws://localhost:25852/');
exampleSocket.onmessage = function(event) {if (event.data === 'reload') {location.reload();}};
TEXT;
        $this->assertEquals($expected, file_get_contents('tests/app/assets/livereload/livereload.js'));
        
        passthru('ps aux | grep "php" > tests/app/psaux');
        $processes = file_get_contents('tests/app/psaux');
        $this->assertNotFalse(strpos($processes, 'vendor/bin/elephfront-robo-live-reload localhost 25852'));
    }

    /**
     * Tests running the task will start the server and create the livereload js file to the proper location.
     *
     * @return void
     */
    public function testWsClient()
    {
        $this->task
            ->jsPath('tests/app/assets/livereload/livereload.js')
            ->run();

        $client = $this->task->getWsClient();
        $this->assertInstanceOf('WebSocket\Client', $client);
    }
}
