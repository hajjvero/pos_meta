<?php

namespace App\Command\Option;

use App\Entity\Option\Option;
use App\Repository\Option\OptionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:option:add',
    description: 'Add a new option to the database'
)]
class OptionCommand extends Command
{
    public function __construct(
        private readonly OptionRepository $optionRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('key', InputArgument::OPTIONAL, 'The option key')
            ->addArgument('value', InputArgument::OPTIONAL, 'The option value')
            ->setHelp('This command allows you to add a new option to the database. If key and value are not provided, you will be prompted to enter them.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Get key from argument or prompt
        $key = $input->getArgument('key');
        if (!$key) {
            $question = new Question('Please enter the option key: ');
            $question->setValidator(function ($value) {
                if (empty(trim($value))) {
                    throw new \RuntimeException('The option key cannot be empty.');
                }
                return trim($value);
            });
            $key = $io->askQuestion($question);
        }

        // Get value from argument or prompt
        $value = $input->getArgument('value');
        if (!$value) {
            $question = new Question('Please enter the option value: ');
            $value = $io->askQuestion($question) ?? '';
        }

        // Check if option already exists
        $existingOption = $this->optionRepository->findOneBy(['optionKey' => $key]);

        if ($existingOption) {
            $io->warning(sprintf('Option with key "%s" already exists with value: "%s"', $key, $existingOption->getOptionValue()));

            if (!$io->confirm('Do you want to update the existing option?', false)) {
                $io->info('Operation cancelled.');
                return Command::SUCCESS;
            }

            $existingOption->setOptionValue($value);
            $this->optionRepository->save($existingOption, true);

            $io->success(sprintf('Option "%s" updated successfully with value: "%s"', $key, $value));
            return Command::SUCCESS;
        }

        // Create new option
        $option = new Option();
        $option->setOptionKey($key);
        $option->setOptionValue($value);

        $this->optionRepository->save($option, true);

        $io->success(sprintf('Option "%s" created successfully with value: "%s"', $key, $value));

        return Command::SUCCESS;
    }
}
