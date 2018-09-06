<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Service\CityService;

class PopulationClearCommand extends ContainerAwareCommand
{
    private $cityService;

    public function __construct($name = null, CityService $cityService){
        parent::__construct();
        $this->cityService = $cityService;
    }
    
    protected function configure()
    {
        $this
            ->setName('population:clear')
            ->setDescription('Clears all cities in database and dump file in json format, directory can be passed in format ex.: /user')
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
        $data = $this->cityService->deleteAllEntriesAndDumpJson($argument);
        
        $output->writeln($data);
    }

}
