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

use Robo\Contract\TaskInterface;
use Robo\Task\Base\loadTasks;
use Robo\Task\BaseTask;

class LiveReload extends BaseTask implements TaskInterface
{

    /**
     * Loads the `taskExec` method this class needs.
     */
    use loadTasks;

    /**
     * Start the LiveReload server as a background task.
     * @return \Robo\Result
     */
    public function run()
    {
        return $this->taskExec('php bin/elephfront-robo-live-reload.php')
            ->background()
            ->run();
    }
}
