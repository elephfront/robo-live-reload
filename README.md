# Robo Live Reload

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?branch=master)](LICENSE.txt)
[![Build Status](https://travis-ci.org/elephfront/robo-live-reload.svg?branch=master)](https://travis-ci.org/elephfront/robo-live-reload)
[![Codecov](https://img.shields.io/codecov/c/github/elephfront/robo-live-reload.svg)](https://github.com/elephfront/robo-live-reload)

This [Robo](https://github.com/consolidation/robo) task starts a Live Reload server when using Elephfront.

If you use the base Elephfront skeleton, it will be fired up when you start the `serve` command. Whenever you edit a file, it will trigger the related commands and triggers a refresh in the browsers connected to the Elephfront server. For instance, if you edit a SASS file, it will compile the SASS source to CSS, minify the results and automatically trigger a refresh in your browser.

The Live Reload server is powered by compilation using the [ratchetphp/Ratchet](https://github.com/ratchetphp/Ratchet) using a WebSocket server.

You can of course use it outside of an Elephfront project. 

## Requirements

- PHP >= 7.1.0
- Robo

## Installation

You can install this Robo task using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require elephfront/robo-live-reload
```

## Implementation

In order to work, this task needs two things : 

- to launch a Live Reload server : this will be done by the `elephfront-robo-live-reload` file in the `bin` directory
- a javascript file that will connect your page to the Live Reload server and listen to message that will force the browser the reload. You will need to include this file (either manually or automatically) in your HTML pages. If you are using the Elephfront project skeleton, this file will automatically be added by the router at the end of your pages.

## Using the task

You can load the task in your RoboFile using the `LoadLiveReloadTaskTrait` trait:

```php
use Elephfront\RoboLiveReload\Task\Loader\LoadLiveReloadTaskTrait;

class RoboFile extends Tasks
{

    use LoadLiveReloadTaskTrait;
    
    public function someTask()
    {
        $this
            ->taskLiveReload()
            ->run();
    }
}
```

The `LiveReload` task has some utility methods that will give you the ability to customize the server launched.

### `host()`

Gives you the ability to change the host the server will be hosted on. By default : `127.0.0.1`.

### `port()`

Gives you the ability to change the port the server will be hosted on. By default : `22222`.

### `bin()`

Gives you the ability to change the bin path where the script that will start the Live Reload server is located. By default : `vendor/bin`.

### `jsPath()`

Gives you the ability to change the path where the javascript file needed to make the browser listen to the Live Reload server and force the refresh will be located. By default, it will put it in the expected location of the build directory of an Elephfront project : `build/system/LiveReload/assets/js/livereload.js`. Based on your project setup, you may have to customize this value.

## Notifying the LiveReload server

To send a message to the Live Reload server so it can propagate it to the browser and the reload triggered, you can use the `sendReloadMessage()` of the task::
 
```php
use Elephfront\RoboLiveReload\Task\Loader\LoadLiveReloadTaskTrait;

class RoboFile extends Tasks
{

    use LoadLiveReloadTaskTrait;
    
    public function someTask()
    {
        $this
            ->taskLiveReload()
            ->run();
            
        // Further down...
        $this
            ->taskLiveReload()
            ->sendReloadMessage();
    }
}
```

This will send a "reload" message to the Live Reload server. If you load the `livereload.js` file created by the task, your browser will automatically reload when this message is received.  
Typically, you will use this method when using a "watch" behavior so a reload can be triggered after your watch handler has been executed.

If you want to send another message to extend the behavior of the Live Reload server, you can have access to the WebSocket client using the `getWsClient()` method and send the message you want :

```php
use Elephfront\RoboLiveReload\Task\Loader\LoadLiveReloadTaskTrait;

class RoboFile extends Tasks
{

    use LoadLiveReloadTaskTrait;
    
    public function someTask()
    {
        $this
            ->taskLiveReload()
            ->run();
            
        // Further down...
        $this
            ->taskLiveReload()
            ->getWsClient()
            ->send('your-message');
    }
}
```

## Contributing

If you find a bug or would like to ask for a feature, please use the [GitHub issue tracker](https://github.com/Elephfront/robo-live-reload/issues).
If you would like to submit a fix or a feature, please fork the repository and [submit a pull request](https://github.com/Elephfront/robo-live-reload/pulls).

### Coding standards

This repository follows the PSR-2 standard. 

## License

Copyright (c) 2017, Yves Piquel and licensed under [The MIT License](http://opensource.org/licenses/mit-license.php).
Please refer to the LICENSE.txt file.
