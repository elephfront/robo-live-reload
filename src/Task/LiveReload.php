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
     * `bin` directory path where the executable file is located.
     *
     * @var string
     */
    protected $binPath;

    /**
     * Instance of a WebSocket client.
     *
     * @var \WebSocket\Client
     */
    protected $wsClient;

    /**
     * LiveReload constructor.
     *
     * @param string $binPath `bin` directory path where the executable file is located.
     */
    public function __construct($binPath = 'vendor/bin/')
    {
        $this->setBinPath($binPath);
    }

    /**
     * Sets the `bin` directory path where the executable file is located.
     *
     * @param $binPath
     * @return self
     */
    protected function setBinPath($binPath)
    {
        $this->binPath = rtrim($binPath, '/') . '/';

        return $this;
    }

    /**
     * Start the LiveReload server as a background task.
     * @return \Robo\Result
     */
    public function run()
    {
        $command = sprintf('php %selephfront-robo-live-reload', $this->binPath);
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
            $this->wsClient = new Client('ws://127.0.0.1:22222/');
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
