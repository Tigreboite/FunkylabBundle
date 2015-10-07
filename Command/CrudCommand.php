<?php

namespace Tigreboite\FunkylabBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CrudCommand extends ContainerAwareCommand
{
    private $em;
    private $emDefault;
    private $router;
    private $mailer;
    private $logger;
    private $verbose;
    private $output;

    protected function configure()
    {
        $this
          ->setName('funkylab:crud')
          ->setDescription('Create a CRUD from entity for Funkylab')
          ->addOption('entity', null, InputOption::VALUE_REQUIRED, 'Entity Class ex Tigreboite\\FunkylabBundle\\Entity\\Post')
          ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Type of the crud to generate : grid|tree|form')
          ->addOption('bundle', null, InputOption::VALUE_REQUIRED, 'Bundle where to create CRUD')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);

        $this->output       = $output;
        $this->logger       = $this->getContainer()->get('logger');
        $this->router       = $this->getContainer()->get('router');
        $this->em           = $this->getContainer()->get('doctrine');
        $type               = $input->getOption('type');
        $entityClass        = $input->getOption('entity');
        $bundle             = $input->getOption('bundle');
        $this->verbose      = $input->getOption('verbose');

        $dialog = $this->getHelper('dialog');

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

        if ($entityClass!="" && class_exists($entityClass)) {
            $entityBase = new $entityClass();
        }else{
            throw new \RuntimeException("Entity doesn't exist");
        }

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
        if(!$type)
        {
            $typeNames = array('datagrid', 'tree', 'form');
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

        dump($entityClass,$bundle,$type);


        // Display Timer
        $this->processEnd($timeStart);
    }

    private function processEnd($timeStart)
    {
        $timeEnd = microtime(true);
        $generationTime = $timeEnd - $timeStart;
        $this->ouputConsole('<info>Done in ' . date('i \m\i\n s \s\e\c', $generationTime) .'</info>');
    }

    private function ouputConsole($txt)
    {
        if($this->verbose)
            $this->output->writeln($txt);
    }

}
