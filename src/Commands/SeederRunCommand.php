<?php

namespace App\Commands;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper;
use App\Class\DB;

class SeederRunCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('run:seeder')
            ->setDescription('Запуск всех Seeders');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln('<info>Старт...<info>');
        $seedersDir = BASE_DIR . '/database/seeders';
        $scanDir = str_replace('php', '', str_replace('.', '', (scandir($seedersDir))));
        $scanDir = array_values(array_filter($scanDir, 'strlen'));

        if (!empty($scanDir)) {

            $db = DB::connect();
            foreach ($scanDir as $seeders) {
                $filePath = BASE_DIR . '/database/seeders/' . $seeders . '.php';
                $seeder = require_once $filePath;

                //dump($seeder);

                if (!empty($seeder->run())) {
                    $db->prepare($seeder->run())->execute();
                    $output->writeln("<info> ---> Успешно выполнен: -->  " . $seeders . '. </info>');
                }

            }

        }

        //dd($scanDir);
        $output->writeln('<info>Готово</info>');
        return Command::SUCCESS;
    }
}