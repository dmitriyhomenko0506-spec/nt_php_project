<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands\RouteListCommand;
use App\Commands\MigrationMakeCommand;

$application = new Application();

// Просто регистрируем наши классы команд
$application->addCommand(new RouteListCommand());
$application->addCommand(new MigrationMakeCommand());

// Symfony сама прочитает $argv и запустит нужный роут
$application->run();