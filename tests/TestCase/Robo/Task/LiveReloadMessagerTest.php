<?php
namespace Elephfront\RoboLiveReload\Tests;

use Elephfront\RoboLiveReload\LiveReloadMessager;
use PHPUnit\Framework\TestCase;
use Ratchet\Server\IoConnection;

class LiveReloadMessagerTest extends TestCase
{

    /**
     * Test the LiveReloadMessager::onOpen method
     *
     * @return void
     */
    public function testOnOpen()
    {
        $messager = $this
            ->getMockBuilder(LiveReloadMessager::class)
            ->setMethods(['output'])
            ->getMock();

        $this->sock = $this
            ->getMockBuilder('\\React\\Socket\\ConnectionInterface')
            ->getMock();

        $messager
            ->expects($this->once())
            ->method('output')
            ->with('New connection to the LiveReload server');

        $messager->onOpen(new IoConnection($this->sock));
    }

    /**
     * Test the LiveReloadMessager::onMessage method
     *
     * @return void
     */
    public function testOnMessage()
    {
        $messager = $this
            ->getMockBuilder(LiveReloadMessager::class)
            ->setMethods(['output'])
            ->getMock();

        $this->sock = $this
            ->getMockBuilder('\\React\\Socket\\ConnectionInterface')
            ->getMock();

        $messager
            ->expects($this->at(2))
            ->method('output')
            ->with('Sending message "Hello" to 1 other connection');

        $connection = $this
            ->getMockBuilder('\\Ratchet\\Server\\IoConnection')
            ->setConstructorArgs([$this->sock])
            ->setMethods(['send'])
            ->getMock();

        $connection
            ->expects($this->once())
            ->method('send')
            ->with('Hello');

        $messager->onOpen(new IoConnection($this->sock));
        $messager->onOpen($connection);
        $messager->onMessage(new IoConnection($this->sock), 'Hello');
    }

    /**
     * Test the LiveReloadMessager::onClose method
     *
     * @return void
     */
    public function testOnClose()
    {
        $messager = $this
            ->getMockBuilder(LiveReloadMessager::class)
            ->setMethods(['output'])
            ->getMock();

        $this->sock = $this
            ->getMockBuilder('\\React\\Socket\\ConnectionInterface')
            ->getMock();

        $messager
            ->expects($this->once())
            ->method('output')
            ->with('Connection has disconnected');

        $messager->onClose(new IoConnection($this->sock));
    }

    /**
     * Test the LiveReloadMessager::onError method
     *
     * @return void
     */
    public function testOnError()
    {
        $messager = $this
            ->getMockBuilder(LiveReloadMessager::class)
            ->setMethods(['output'])
            ->getMock();

        $this->sock = $this
            ->getMockBuilder('\\React\\Socket\\ConnectionInterface')
            ->getMock();

        $messager
            ->expects($this->once())
            ->method('output')
            ->with('An error has occurred: `An error`');

        $messager->onError(new IoConnection($this->sock), new \Exception('An error'));
    }
}
