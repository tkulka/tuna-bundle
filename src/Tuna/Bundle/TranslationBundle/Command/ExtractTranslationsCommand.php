<?php

namespace TheCodeine\TranslationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExtractTranslationsCommand extends Command
{
    private static $PASS_OPTIONS = [
        'options' => [
            'prefix',
            'output-format',
            'clean'
        ],
        'arguments' => [
            'bundle',
        ]
    ];

    /**
     * @var array
     */
    private $locales;

    /**
     * @var Command
     */
    private $extractCommand;

    public function __construct($locales = [])
    {
        parent::__construct(null);
        $this->locales = $locales;
    }

    protected function configure()
    {
        $this
            ->setName('tuna:translations:extract')
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'Override the default prefix', '')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'The bundle name or directory where to load the messages, defaults to app/Resources folder')
            ->addOption('output-format', null, InputOption::VALUE_OPTIONAL, 'Override the default output format', 'yml')
            ->addOption('clean', null, InputOption::VALUE_NONE, 'Should clean not found messages')
            ->addOption('locales', null, InputOption::VALUE_OPTIONAL, 'Override default tuna locales (comma separeted list)')
            ->setDescription('Extracts translations from app to yaml files')
            ->setHelp('Use this command to generate yml translation files in app/Resources/translations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->extractCommand = $this->getApplication()->find('translation:update');
        $arguments = $this->resolveArguments($input);

        $locales = $input->getOption('locales') ?
            array_map('trim', explode(',', $input->getOption('locales'))) :
            $this->locales;

        foreach ($locales as $locale) {
            $this->updateTranslations($locale, $arguments, $output);
        }
    }

    private function updateTranslations($locale, $arguments, OutputInterface $output)
    {
        $commandInput = new ArrayInput($arguments + [
                'locale' => $locale,
            ]);
        return $this->extractCommand->run($commandInput, $output);
    }

    private function resolveArguments(InputInterface $input)
    {
        $arguments = [
            '--force' => true,
        ];

        foreach (self::$PASS_OPTIONS['options'] as $option) {
            if (($value = $input->getOption($option)) !== null) {
                $arguments["--$option"] = $value;
            }
        }
        foreach (self::$PASS_OPTIONS['arguments'] as $argument) {
            if (($value = $input->getArgument($argument)) !== null) {
                $arguments[$argument] = $value;
            }

        }

        return $arguments;
    }
}
