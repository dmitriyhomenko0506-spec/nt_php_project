<?php

define('BASE_DIR', __DIR__);
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands\RouteListCommand;
use App\Commands\MigrationMakeCommand;
use App\Commands\MigrationRunCommand;
use App\Commands\MigrationRollbackCommand;

$application = new Application();

$application->addCommand(new RouteListCommand());
$application->addCommand(new MigrationMakeCommand());
$application->addCommand(new MigrationRunCommand());
$application->addCommand(new MigrationRollbackCommand());

// Symfony сама прочитает $argv и запустит нужный роут
$application->run();