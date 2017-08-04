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

class LiveReload extends BaseTask implements TaskInterface
{

    use ExecCommand;

    protected $binPath;

    public function __construct($binPath)
    {
        $this->setBinPath($binPath);
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

    protected function setBinPath($binPath)
    {
        $this->binPath = rtrim($binPath, '/') . '/';
    }
}
