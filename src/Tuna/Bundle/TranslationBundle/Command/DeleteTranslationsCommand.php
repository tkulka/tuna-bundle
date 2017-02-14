<?php

namespace TheCodeine\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeleteTranslationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tuna:translations:delete')
            ->setDescription('Deletes all DB translations')
            ->setHelp('Use this command to delete all DB translations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->isInteractive()) {
            if (!$this->askConfirmation($input, $output, '<question>Careful, database translations will be purged. Do you want to continue y/N ?</question>', false)) {
                return;
            }
        }

        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $em->createQuery('DELETE LexikTranslationBundle:Translation')->execute();
        $em->createQuery('DELETE LexikTranslationBundle:TransUnit')->execute();
        $em->createQuery('DELETE LexikTranslationBundle:File')->execute();

        $output->writeln('<info>Translations deleted.</info>');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $question
     * @param bool $default
     *
     * @return bool
     */
    private function askConfirmation(InputInterface $input, OutputInterface $output, $question, $default)
    {
        if (!class_exists(ConfirmationQuestion::class)) {
            $dialog = $this->getHelperSet()->get('dialog');

            return $dialog->askConfirmation($output, $question, $default);
        }

        $questionHelper = $this->getHelperSet()->get('question');
        $question = new ConfirmationQuestion($question, $default);

        return $questionHelper->ask($input, $output, $question);
    }
}
