<?php

namespace Tigreboite\FunkylabBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Tigreboite\FunkylabBundle\Generator\GeneratorCrud;

class CrudCommand extends ContainerAwareCommand
{
    private $em;
    private $verbose;
    private $output;

    protected function configure()
    {
        $this
          ->setName('funkylab:crud')
          ->setDescription('Create a CRUD from entity for Funkylab')
          ->addOption('entity', null, InputOption::VALUE_REQUIRED, 'Entity Class ex Tigreboite\\FunkylabBundle\\Entity\\Post')
          ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Type of the crud to generate : datagrid|sortable|simpleform')
          ->addOption('bundle', null, InputOption::VALUE_REQUIRED, 'Bundle where to create CRUD')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);

        $this->output       = $output;
        $this->em           = $this->getContainer()->get('doctrine');
        $this->verbose      = $input->getOption('verbose');
        $type               = $input->getOption('type');
        $entityClass        = $input->getOption('entity');
        $entityClass        = str_replace('/','\\',$entityClass);
        $bundle             = $input->getOption('bundle');
        $dialog             = $this->getHelper('dialog');
        // Get Entity
        if(!$entityClass)
        {
            $entityClass = $dialog->askAndValidate(
              $output,
              '<question>Please enter the entity : </question>',
              function ($answer) {
                  if(trim($answer)=="")
                  {
                      throw new \RuntimeException("You must set an entity");
                  }
                  return $answer;
              }
            );

            $output->writeln('You enter : '.$entityClass);
        }

        // Test Entity
        if (!class_exists($entityClass)) {
            throw new \RuntimeException("Entity doesn't exist");
        }

        // Get Bundle
        if(!$bundle)
        {
            $bundle = $dialog->askAndValidate(
              $output,
              '<question>Please enter the name of the bundle : </question>',
              function ($answer) {
                  if ($answer=="" || 'Bundle' !== substr($answer, -6)) {
                      throw new \RuntimeException('The name of the bundle should be suffixed with \'Bundle\'');
                  }
                  return $answer;
              }
            );
            $output->writeln('You enter : '.$bundle);
        }

        // Test Bundle
        if(!array_key_exists($bundle,$this->getContainer()->getParameter('kernel.bundles')))
        {
            throw new \RuntimeException("Bundle doesn't exist");
        }

        // Get Type to generate
        if(!$type)
        {
            $typeNames = array('datagrid', 'simpleform', 'sortable');
            $selected = $dialog->select(
              $output,
              'Please select the type of CRUD (default to datagrid)',
              $typeNames,
              0,
              false,
              'Value "%s" is invalid',
              true // enable multiselect
            );
            $selectedColors = array_map(function ($c) use ($typeNames) {
                return $typeNames[$c];
            }, $selected);
            $output->writeln('You have just selected: ' . implode(', ', $selectedColors));
            $type = implode(', ', $selectedColors);
        }

        // Generate CRUD
        $res = new GeneratorCrud($entityClass, $bundle, $type);
        $this->output->writeln("<info>Files generated in</info> : ".$bundle);
        $this->output->writeln($res->getFiles());

        // Display Timer
        $this->processEnd($timeStart);
    }

    private function processEnd($timeStart)
    {
        $timeEnd = microtime(true);
        $generationTime = $timeEnd - $timeStart;
        $this->output->writeln('<info>Done in ' . date('i \m\i\n s \s\e\c', $generationTime) .'</info>');
    }

}

