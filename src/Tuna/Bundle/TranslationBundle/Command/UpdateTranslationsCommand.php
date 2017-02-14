<?php

namespace TheCodeine\TranslationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTranslationsCommand extends Command
{
    /**
     * @var array
     */
    private $locales;

    public function __construct($locales = [])
    {
        parent::__construct(null);
        $this->locales = $locales;
    }

    protected function configure()
    {
        $this
            ->setName('tuna:translations:update')
            ->addOption('locales', null, InputOption::VALUE_OPTIONAL, 'Override default tuna locales (comma separeted list)')
            ->addOption('domains', 'd', InputOption::VALUE_OPTIONAL, 'Only imports files for given domains (comma separated).')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force import, replace database content.')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'Import translations for this specific bundle (if no bundle provided app/Resources/translations will be used).', null)
            ->setDescription('Updates DB translations in translation panel')
            ->setHelp('Use this command to update DB translations available in translation panel');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locales = $input->getOption('locales') ?
            array_map('trim', explode(',', $input->getOption('locales'))) :
            $this->locales;

        $arguments = [
            '--cache-clear' => true,
            '--merge' => true,
            '--globals' => $input->getArgument('bundle') == null,
            '--force' => $input->getOption('force'),
            '--locales' => $locales,
            '--domains' => $input->getOption('domains'),
            'bundle' => $input->getArgument('bundle'),
        ];

        $commandInput = new ArrayInput($arguments);
        $this->getApplication()->find('lexik:translations:import')->run($commandInput, $output);
    }
}
