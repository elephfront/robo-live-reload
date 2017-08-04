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
     * Exposes the LiveReload task.
     *
     * @return \Elephfront\RoboLiveReload\Task\LiveReload Instance of the Sass Task
     */
    protected function taskLiveReload()
    {
        return $this->task(LiveReload::class);
    }
}
