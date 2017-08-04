<?php
/**
 * Copyright (c) Yves Piquel (http://www.havokinspiration.fr)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Yves Piquel (http://www.havokinspiration.fr)
 * @link          http://github.com/elephfront/robo-live-reload
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);
namespace Elephfront\RoboLiveReload\Task;

use Robo\Common\ExecCommand;
use Robo\Contract\TaskInterface;
use Robo\Task\BaseTask;
use WebSocket\Client;

/**
 * Class LiveReload
 *
 * In charge on launching the WebSocket server that will act as the proxy between the browser and the Robo script
 * running.
 */
class LiveReload extends BaseTask implements TaskInterface
{

    use ExecCommand;

    /**
     * Instance of a WebSocket client.
     *
     * @var \WebSocket\Client
     */
    protected $wsClient;

    /**
     * `bin` directory path where the executable file is located.
     *
     * @var string
     */
    protected $bin;

    /**
     * Port the WebSocket server should be opened to.
     *
     * @var int
     */
    protected $port;

    /**
     * Host the WebSocket server should be hosted under.
     *
     * @var string
     */
    protected $host;

    /**
     * Sets the port the WebSocket server should expose.
     *
     * @param int $port
     * @return $this
     */
    public function port($port = 22222)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Sets the `bin` directory path where the executable file is located.
     *
     * @param string $bin Host the WebSocket server should be hosted under.
     * @return $this
     */
    public function bin($bin = 'vendor/bin/')
    {
        $this->bin = rtrim($bin, '/') . '/';

        return $this;
    }

    /**
     * Sets the `bin` directory path where the executable file is located.
     *
     * @param string $host `bin` directory path where the executable file is located.
     * @return $this
     */
    public function host($host = '127.0.0.1')
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Start the LiveReload server as a background task.
     * @return \Robo\Result
     */
    public function run()
    {
        $command = sprintf('php %selephfront-robo-live-reload %s %d', $this->bin, $this->host, $this->port);
        $this->background(true);

        return $this->executeCommand($command);
    }

    /**
     * Gets a WebSocker Client instance in order to send message to the server.
     *
     * @return \WebSocket\Client Instance of a WebSocket client
     */
    public function getWsClient()
    {
        if (!($this->wsClient instanceof Client)) {
            $address = sprintf('ws://%s:%d/', $this->host, $this->port);
            $this->wsClient = new Client($address);
        }

        return $this->wsClient;
    }

    /**
     * Sends a reload message to the WebSocket server.
     *
     * @return void
     */
    public function sendReloadMessage()
    {
        $this->getWsClient()->send('reload');
    }
}
