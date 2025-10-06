<?php

namespace Ojowbe\CombellCli\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'combell:list',
    description: 'List all available Combell API commands from the SDK.'
)]
class ListCombellCommands extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Correct absolute path to the Combell API command folder
	$baseDir = realpath(__DIR__ . '/../../..') . '/combell-cli/vendor/tomcan/combell-api/src/Command';


        if (!is_dir($baseDir)) {
            $output->writeln("<error>Combell API library not found at: $baseDir</error>");
            return Command::FAILURE;
        }

        $output->writeln("<info>Available Combell API commands:</info>\n");

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($baseDir));
        $commands = [];

        foreach ($rii as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            // Convert path to namespace style, e.g. Accounts\ListAccounts
            $relative = str_replace($baseDir . '/', '', $file->getPathname());
            $relative = str_replace(['/', '.php'], ['\\', ''], $relative);
            $commands[] = $relative;
        }

        sort($commands);

        foreach ($commands as $cmd) {
            $filePath = $baseDir . '/' . str_replace('\\', '/', $cmd) . '.php';
            $constructorParams = '';

            if (is_readable($filePath)) {
                $code = file_get_contents($filePath);
                // Extract the constructor argument list, if any
                if (preg_match('/function __construct\((.*?)\)/s', $code, $m)) {
                    $constructorParams = trim($m[1]);
                }
            }

            $output->writeln(sprintf(
                " - <comment>%s</comment>%s",
                $cmd,
                $constructorParams ? " (params: {$constructorParams})" : ''
            ));
        }

        $output->writeln("\nTip: run ./bin/console combell:run \"<Command>\" --params='[...]'");

        return Command::SUCCESS;
    }
}

