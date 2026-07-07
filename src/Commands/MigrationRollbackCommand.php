<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper;
use App\Class\DB;

class MigrationRollbackCommand extends Command
{
    // Настраиваем имя команды для вызова в терминале
    protected function configure(): void
    {
        $this
            ->setName('rollback:migration')
            ->setDescription('Откат всех последовательных миграций (метод down)');
    }

    // Логика перебора файлов и вызова их методов up()
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Получаем LastBatch</info>');
        $lastBarch = $this->LastBatch();
        if (isset($lastBarch)) {
            $output->writeln('<info> Получаем список Миграций по Batch = ' . $lastBarch . ' </info>');
            $LastMigrationDb = $this->GetLastListMigrationDb($lastBarch);
        } else {
            $output->writeln('<info>Таблица Migration пустая </info>');
            die;
        }
        if (!empty($LastMigrationDb)) {
            $this->RollbackBatch($LastMigrationDb, $output);
        }

        $output->writeln('<info>Готово!</info>');

        return Command::SUCCESS;
    }

    private function RollbackBatch($LastMigrationDb, $output)
    {
        $db = DB::connect();
        $quary = $db->prepare("DELETE FROM migration WHERE migration = :name");

        foreach ($LastMigrationDb as $migrationName) {

            // Формируем полный путь к файлу миграции
            $filePath = BASE_DIR . '/database/migrations/' . $migrationName . '.php';

            //Глобально отключаем проверку внешних ключей
            $db->exec("SET FOREIGN_KEY_CHECKS = 0;");

            if (file_exists($filePath)) {
                // Подключаем файл $migration->down()
                $migration = require_once $filePath;
                if (!empty($migration->down())) {

                    //dump($migration->down());
                    $db->exec($migration->down());
                    $quary->execute([':name' => $migrationName]);
                    $output->writeln("<info> ---> Миграция успешно удалена: -->  " . $migrationName . '.php </info>');
                } else {
                    $output->writeln("<info> ---> Ошибка метода Down: -->  " . $migrationName . '.php </info>');
                    continue;
                }
            } else {

                $output->writeln("<info> ---> Ошибка миграции - нет файла: -->  " . $migrationName . '.php </info>');
            }
        }
    }

    private function LastBatch()
    {
        $db = DB::connect();
        $LastBatch = $db->query("SELECT MAX(batch) FROM migration")->fetchColumn();
        return $LastBatch;
    }

    private function GetLastListMigrationDb($lastBarch)
    {
        $db = DB::connect();
        $LastMigrationDb = $db->query("SELECT migration FROM migration Where batch = $lastBarch")->fetchAll(\PDO::FETCH_COLUMN);
        return $LastMigrationDb;
    }
}
