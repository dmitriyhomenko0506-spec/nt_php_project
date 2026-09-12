<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeederAddCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('add:seeder')
            ->setDescription('Создает новый файл Seeder')
            // Настраиваем обязательный аргумент - имя миграции
            ->addArgument('name', InputArgument::REQUIRED, 'Название Seeader');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1. Получаем имя миграции от пользователя и очищаем его
        $seederName = trim($input->getArgument('name'));

        // Переводим в регистр snake_case на случай, если пользователь ввел CamelCase
        $seederName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $seederName));

        // 2. Определяем путь к папке с миграциями
        $seedersDir = BASE_DIR . '/database/seeders';

        // Если папки database/migrations еще нет, создаем её автоматически
        if (!is_dir($seedersDir)) {
            mkdir($seedersDir, 0777, true);
        }

        // 3. Формируем уникальное имя файла
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_{$seederName}.php";
        $fullPath = "{$seedersDir}/{$filename}";

        // 4. Генерируем базовый каркас для файла 

        $stub = <<<PHP
<?php

//Seeder: {$seederName}

return new class { 
    public function run(): string
    {
        return "";
    }

};
PHP;

        //dd($fullPath);

        // 5. Записываем файл 
        if (file_put_contents($fullPath, $stub) === false) {
            $output->writeln('<error> Не удалось создать файл seeder.</error>');
            return Command::FAILURE;
        }

        // Выводим красивое сообщение об успехе
        $output->writeln('');
        $output->writeln("<info>Seeder успешно создан!</info>");
        $output->writeln("<comment>database/seeders/{$filename}</comment>");
        $output->writeln('');

        return Command::SUCCESS;
    }
}
