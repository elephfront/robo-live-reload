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

use PHPUnit\Framework\TestCase;
use Robo\Collection\CollectionBuilder;
use Robo\Robo;
use Robo\Tasks;

/**
 * Class FakeRoboFile
 *
 * Fake Robo file instance used to test the Trait.
 */
class FakeRoboFile extends Tasks
{
    use LoadLiveReloadTaskTrait {
        taskLiveReload as public;
    }

    public function getLiveReload()
    {
        return $this->liveReload;
    }
}

/**
 * Class LoadLiveReloadTaskTraitTest
 *
 * Tests the `LoadLiveReloadTaskTrait`.
 */
class LoadLiveReloadTaskTraitTest extends TestCase
{

    /**
     * Instance of the Fake robo task used for testing
     *
     * @var \Elephfront\RoboLiveReload\Task\Loader\FakeRoboFile
     */
    protected $roboCommandFileInstance;

    /**
     * Fake CommandFactory instance
     *
     * @var \Consolidation\AnnotatedCommand\AnnotatedCommandFactory
     */
    protected $commandFactory;

    /**
     * Fake app instance
     * @var \Robo\Application
     */
    protected $app;

    /**
     * Tests that calling the trait method will always return the same instance if called consecutively.
     *
     * @return void
     */
    public function testTrait()
    {
        $container = Robo::createDefaultContainer();

        $this->app = $container->get('application');
        $config = $container->get('config');
        $this->commandFactory = $container->get('commandFactory');
        $this->roboCommandFileInstance = new FakeRoboFile();
        $builder = CollectionBuilder::create($container, $this->roboCommandFileInstance);
        $this->roboCommandFileInstance->setBuilder($builder);
        $commandList = $this->commandFactory->createCommandsFromClass($this->roboCommandFileInstance);
        foreach ($commandList as $command) {
            $this->app->add($command);
        }

        $taskLiveReload = $this->roboCommandFileInstance->taskLiveReload()->port(25255);
        $originalTask = (array)$taskLiveReload->getCollectionBuilderCurrentTask();
        $taskCalledAgain = (array)$this->roboCommandFileInstance->taskLiveReload()->getCollectionBuilderCurrentTask();

        $this->assertEquals($taskCalledAgain["\0*\0port"], $originalTask["\0*\0port"]);
    }
}
