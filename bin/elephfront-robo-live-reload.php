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
require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Elephfront\RoboLiveReload\LiveReloadMessager;

$ws = new \Ratchet\WebSocket\WsServer(new LiveReloadMessager());
$ws->disableVersion(0);

$server = IoServer::factory(new HttpServer($ws), 22222, '127.0.0.1');
$server->run();