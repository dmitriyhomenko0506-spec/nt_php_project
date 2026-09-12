<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



class MigrationMakeCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('add:migration')
            ->setDescription('Создает новый файл базы данных (миграцию)')
            // Настраиваем обязательный аргумент - имя миграции
            ->addArgument('name', InputArgument::REQUIRED, 'Название миграции');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1. Получаем имя миграции от пользователя и очищаем его
        $migrationName = trim($input->getArgument('name'));

        // Переводим в регистр snake_case на случай, если пользователь ввел CamelCase
        $migrationName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $migrationName));

        // 2. Определяем путь к папке с миграциями
        $migrationsDir = __DIR__ . '/../../database/migrations';

        // Если папки database/migrations еще нет, создаем её автоматически
        if (!is_dir($migrationsDir)) {
            mkdir($migrationsDir, 0777, true);
        }

        // 3. Формируем уникальное имя файла с таймстампом
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_{$migrationName}.php";
        $fullPath = "{$migrationsDir}/{$filename}";

        // 4. Генерируем базовый каркас для файла миграции

        $stub = <<<PHP
<?php

//Migration: {$migrationName}

return new class { 
    public function up(): void
    {
        // Логика создания / изменения таблицы
    }

    public function down(): void
    {
        // Логика отката изменений
    }
};
PHP;

        // 5. Записываем файл 
        if (file_put_contents($fullPath, $stub) === false) {
            $output->writeln('<error> Не удалось создать файл миграции.</error>');
            return Command::FAILURE;
        }

        // Выводим красивое сообщение об успехе
        $output->writeln('');
        $output->writeln("<info>Миграция успешно создана!</info>");
        $output->writeln("<comment>database/migrations/{$filename}</comment>");
        $output->writeln('');

        return Command::SUCCESS;
    }
}
