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
namespace Elephfront\RoboLiveReload\Task\Loader;

use Elephfront\RoboLiveReload\Task\LiveReload;

trait LoadLiveReloadTaskTrait
{

    /**
     * Instance of the LiveReload task. Needed in order to easily send message to the WebSocket server.
     *
     * @var \Elephfront\RoboLiveReload\Task\LiveReload
     */
    public $liveReload;

    /**
     * Exposes the LiveReload task.
     *
     * @return \Elephfront\RoboLiveReload\Task\LiveReload Instance of the Sass Task
     */
    protected function taskLiveReload()
    {
        if ($this->liveReload === null) {
            $this->liveReload = $this->task(LiveReload::class);
        }

        return $this->liveReload;
    }
}
