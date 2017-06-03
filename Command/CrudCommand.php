<?php

namespace Tigreboite\FunkylabBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Tigreboite\FunkylabBundle\Generator\GeneratorCrud;

class CrudCommand extends ContainerAwareCommand
{
    private $output;
    private $input;

    protected function configure()
    {
        $this
            ->setName('funkylab:crud')
            ->setDescription('Create a CRUD from entity for Funkylab')
            ->addOption('entity', null, InputOption::VALUE_REQUIRED, 'Entity Class ex AppbBundle\\Entity\\Post')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Type of the crud to generate : datagrid|sortable|simpleform')
            ->addOption('bundle', null, InputOption::VALUE_REQUIRED, 'Bundle where to create CRUD');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $timeStart = microtime(true);

        $this->input = $input;
        $this->output = $output;
        $type = $input->getOption('type');
        $entityClass = $input->getOption('entity');
        $entityClass = str_replace('/', '\\', $entityClass);
        $bundle = $input->getOption('bundle');

        // Get Entity
        if (!$entityClass) {
            $entityClass = $this->askQuestion("Please enter the entity");
            $output->writeln('You enter : ' . $entityClass);
        }

        // Test Entity
        if (!class_exists($entityClass)) {
            throw new \RuntimeException("Entity doesn't exist");
        }

        // Get Bundle
        if (!$bundle) {
            $bundle = $this->askQuestion("Please enter the name of the bundle");
            $output->writeln('You enter : ' . $bundle);
        }

        // Test Bundle
        if (!array_key_exists($bundle, $this->getContainer()->getParameter('kernel.bundles'))) {
            throw new \RuntimeException("Bundle doesn't exist");
        }

        // Get Type to generate
        if (!$type) {
            $choices = array('datagrid', 'simpleform', 'sortable');

            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'Please select the type of CRUD (default to datagrid)',
                $choices,
                null
            );
            $question->setValidator(function ($value) {
                if (trim($value) == '') {
                    throw new \Exception('This can\'t not be empty');
                }

                return $value;
            });
            $type = $helper->ask($this->input, $this->output, $question);

            $output->writeln('You have just selected: ' . $type);
        }

        // Generate CRUD
        $res = new GeneratorCrud($entityClass, $bundle, $type);
        $this->output->writeln('<info>Files generated in</info> : ' . $bundle);
        $this->output->writeln($res->getFiles());

        // Display Timer
        $this->processEnd($timeStart);
    }

    private function askQuestion($question)
    {
        $helper = $this->getHelper('question');
        $question = new Question("<info>" . $question . " ?</info> ");
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('This can\'t not be empty');
            }

            return $value;
        });
        return $helper->ask($this->input, $this->output, $question);
    }

    private function processEnd($timeStart)
    {
        $timeEnd = microtime(true);
        $generationTime = $timeEnd - $timeStart;
        $this->output->writeln('<info>Done in ' . date('i \m\i\n s \s\e\c', $generationTime) . '</info>');
    }
}
