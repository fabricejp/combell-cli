<?php

namespace Ojowbe\CombellCli\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TomCan\CombellApi\Common\Api;
use TomCan\CombellApi\Common\HmacGenerator;
use TomCan\CombellApi\Adapter\GuzzleAdapter;

#[AsCommand(
    name: 'combell:run',
    description: 'Execute a Combell API command via CLI.'
)]
class CombellCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument(
                'commandClass',
                InputArgument::REQUIRED,
                'Command class to execute (e.g. Accounts\\ListAccounts)'
            )
            ->addOption(
                'params',
                null,
                InputOption::VALUE_REQUIRED,
                'JSON array of constructor parameters',
                '[]'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = $_ENV['COMBELL_API_KEY'] ?? null;
        $sec = $_ENV['COMBELL_API_SECRET'] ?? null;

        if (!$key || !$sec) {
            $output->writeln('<error>Missing COMBELL_API_KEY or COMBELL_API_SECRET in .env</error>');
            return Command::FAILURE;
        }

        $classPart = $input->getArgument('commandClass');
        $fqcn = '\\TomCan\\CombellApi\\Command\\' . $classPart;

        if (!class_exists($fqcn)) {
            $output->writeln("<error>Command class not found: $fqcn</error>");
            return Command::FAILURE;
        }

        $params = json_decode($input->getOption('params'), true);
        if ($params === null && json_last_error() !== JSON_ERROR_NONE) {
            $output->writeln('<error>Invalid JSON for --params</error>');
            return Command::FAILURE;
        }

        try {
            $api = new Api(new GuzzleAdapter(), new HmacGenerator($key, $sec));
            $reflection = new \ReflectionClass($fqcn);
            $command = $reflection->newInstanceArgs($params);
            $response = $api->executeCommand($command);

            // 🔍 normalize all nested data, including private properties via reflection
            $normalize = function ($data) use (&$normalize) {
                if (is_array($data)) {
                    return array_map($normalize, $data);
                } elseif (is_object($data)) {
                    $ref = new \ReflectionClass($data);
                    $props = [];
                    foreach ($ref->getProperties() as $prop) {
                        $prop->setAccessible(true);
                        $props[$prop->getName()] = $normalize($prop->getValue($data));
                    }
                    return $props;
                }
                return $data;
            };

            $normalized = $normalize($response);

            $output->writeln(json_encode($normalized, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } catch (\Throwable $e) {
            $output->writeln(json_encode([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], JSON_PRETTY_PRINT));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

