<?php

namespace App\Commands;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper;
use App\Class\DB;

class MigrationRunCommand extends Command
{
    // Настраиваем имя команды для вызова в терминале
    protected function configure(): void
    {
        $this
            ->setName('run:migration')
            ->setDescription('Запуск всех последовательных миграций (метод up)');
    }

    // Логика перебора файлов и вызова их методов up()
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Запуск Создание/проверки таблицы Migration...</info>');
        $this->createTableMigration();
        $output->writeln('<info>Запуск Создание/проверки таблицы Migration успешно прошло...</info>');
        $output->writeln('<info>Запуск миграций...</info>');
        $this->InsertAndRunMigrations($output);
        $output->writeln('<info>Готово!</info>');

        return Command::SUCCESS;
    }

    private function InsertAndRunMigrations($output)
    {
        $maxBatch = $this->GetMaxBatch();
        $arrayDirMigration = $this->GetScanDirMigration();
        $arrayMigrationDb = $this->GetListMigrationDb();
        $newMigrations = array_diff($arrayDirMigration, $arrayMigrationDb);
        sort($newMigrations);

        if (empty($newMigrations)) {
            $output->writeln('<info> --> Нет новых миграций</info>');
            die;
        }

        $db = DB::connect();
        $quary = $db->prepare("INSERT INTO migration (migration, batch) VALUES (:name, :batch)");

        foreach ($newMigrations as $migrationName) {

            // Формируем полный путь к файлу миграции
            $filePath = BASE_DIR . '/database/migrations/' . $migrationName . '.php';
            //dump($filePath);
            // Глобально отключаем проверку внешних ключей для этой сессии PDO
            $db->exec("SET FOREIGN_KEY_CHECKS = 0;");

            if (file_exists($filePath)) {
                // Подключаем файл $migration->up()
                $migration = require_once $filePath;
                if (!empty($migration->up())) {
                    $db->prepare($migration->up())->execute();
                    $quary->execute([
                        ':name' => $migrationName,
                        ':batch' => $maxBatch
                    ]);
                    $output->writeln("<info> ---> Миграция успешно выполнена и записана: -->  " . $migrationName . '.php </info>');
                } else {
                    $output->writeln("<info> ---> Ошибка метода UP: -->  " . $migrationName . '.php </info>');
                    continue;
                }
            } else {

                $output->writeln("<info> ---> Ошибка миграции - нет файла: -->  " . $migrationName . '.php </info>');
            }
        }
    }


    private function createTableMigration(): void
    {
        $db = DB::connect();
        $result = $db->prepare('CREATE TABLE IF NOT EXISTS migration ( 
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    migration VARCHAR(255) NOT NULL UNIQUE, 
                    batch SMALLINT UNSIGNED NOT NULL);
              ')
            ->execute();

        if (!$result) {
            throw new RuntimeException('Error create table migration');
        }

    }

    private function GetScanDirMigration()
    {
        $migrationsDir = BASE_DIR . '/database/migrations';
        $scanDir = str_replace('php', '', str_replace('.', '', (scandir($migrationsDir))));
        $scanDir = array_values(array_filter($scanDir, 'strlen'));
        return $scanDir;
    }

    private function GetMaxBatch()
    {
        $db = DB::connect();
        $maxBatch = $db->query("SELECT MAX(batch) FROM migration")->fetchColumn();
        return $maxBatch + 1;
    }

    private function GetListMigrationDb()
    {
        $db = DB::connect();
        $MigrationDb = $db->query("SELECT migration FROM migration")->fetchAll(\PDO::FETCH_COLUMN);
        return $MigrationDb;

    }

}