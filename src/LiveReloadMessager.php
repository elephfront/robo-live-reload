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
namespace Elephfront\RoboLiveReload;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Class LiveReloadMessager
 *
 * In charge of storing connections to the Ratchet server and dispatching messages to them when a message is sent to
 * the server.
 */
class LiveReloadMessager implements MessageComponentInterface
{

    /**
     * Object storage where the connection to the WebSocket server are stored
     *
     * @var \SplObjectStorage
     */
    protected $clients;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Called when a connection is opened to the WebSocket server.
     * It will store the connection to the `clients` property.
     *
     * @param \Ratchet\ConnectionInterface $connection
     * @return void
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients->attach($connection);

        echo sprintf('New connection ! (%s)\n', $connection->resourceId);
    }

    /**
     * Called when a connection is sending a message to the WebSocket server.
     * It will store the connection to the `clients` property.
     *
     * @param \Ratchet\ConnectionInterface $connection Connection sending the $message.
     * @param string $message Message being sent.
     * @return void
     */
    public function onMessage(ConnectionInterface $connection, $message)
    {
        $targetCount = count($this->clients) - 1;
        echo sprintf(
            'Connection `%d` sending message "%s" to %d other connection%s' . "\n",
            $connection->resourceId,
            $message,
            $targetCount,
            $targetCount == 1 ? '' : 's'
        );

        foreach ($this->clients as $client) {
            if ($connection !== $client) {
                $client->send($message);
            }
        }
    }

    /**
     * Called when a connection is closing from the WebSocket server.
     * It will remove the connection from the `clients` property.
     *
     * @param \Ratchet\ConnectionInterface $connection Connection sending the $message.
     * @return void
     */
    public function onClose(ConnectionInterface $connection)
    {
        $this->clients->detach($connection);

        echo sprintf('Connection `%s` has disconnected\n', $connection->resourceId);
    }

    /**
     * Called when a connection encounters an error.
     * It will remove the connection from the `clients` property.
     *
     * @param \Ratchet\ConnectionInterface $connection Connection sending the $message.
     * @param \Exception $exception The exception thrown
     * @return void
     */
    public function onError(ConnectionInterface $connection, \Exception $exception) {
        echo sprintf('An error has occurred: `%s`\n', $exception->getMessage());

        $connection->close();
    }
}
