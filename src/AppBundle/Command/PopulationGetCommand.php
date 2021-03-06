<?php

namespace AppBundle\Command;

use AppBundle\Service\OpenDataSoftService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulationGetCommand extends ContainerAwareCommand
{

    private $open;

    public function __construct($name = null, OpenDataSoftService $open){

        parent::__construct();
        $this->open = $open;
        
    }

    protected function configure()
    {
        $this
            ->setName('population:get')
            ->setDescription('Get cities from external API, argument can be passed in integer format')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }
        $message = $this->open->getCities($argument);
        $output->writeln($message);
    }

}
