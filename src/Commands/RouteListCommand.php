<?php

namespace App\Commands;

use App\Route\Route;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends Command
{
    // Настраиваем имя и описание команды
    protected function configure(): void
    {
        $this->setName('route:list')
            ->setDescription('Выводит список всех зарегистрированных роутов');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Подключаем файл с роутами Web
        $routesFileWeb = __DIR__ . '/../../Route/web/RouteListWeb.php';


        //Если файла нет ошибка
        if (!file_exists($routesFileWeb)) {
            $output->writeln('<error>Файл RouteListWeb.php не найден!</error>');
            return Command::FAILURE;
        }

        //Подключаем файл с роутами API
        $routesFileApi = __DIR__ . '/../../Route/api/RouteListApi.php';


        //Если файла нет ошибка
        if (!file_exists($routesFileApi)) {
            $output->writeln('<error>Файл RouteListApi.php не найден!</error>');
            return Command::FAILURE;
        }

        // Подключаем файл Web роутов 
        require_once $routesFileWeb;

        // Подключаем файл API роутов
        require_once $routesFileApi;

        // Теперь извлекаем ВСЕ накопленные роуты из класса Route
        $registeredRoutes = Route::getRoutes();

        //Если марштрутов нет ошибка
        if (empty($registeredRoutes)) {
            $output->writeln('<comment>Маршруты не зарегистрированы или не найдены.</comment>');
            return Command::SUCCESS;
        }

        // 4. Форматируем данные для таблицы Symfony Console
        $tableRows = [];
        foreach ($registeredRoutes as $route) {



            // Форматируем Action (Контроллер - Метод)
            $action = is_array($route['action'])
                ? implode(' - ', $route['action'])
                : (string) $route['action'];

            // Проверяем наличие Middleware
            $middleware = isset($route['middleware']) ? $route['middleware'] : 'None';

            $tableRows[] = [
                $route['type'] ?? '',
                $route['method'] ?? '',
                $route['uri'] ?? '',
                $action,
                $middleware
            ];
        }

        // 5. Рендерим красивую таблицу в консоли
        $table = new Table($output);
        $table->setHeaders(['Type', 'Method', 'URI', 'Action', 'Middleware'])
            ->setRows($tableRows);

        $table->render();

        return Command::SUCCESS;
    }


}
