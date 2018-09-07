<?php

namespace AppBundle\Service;

use AppBundle\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class CityService{

    private $em;
    private $validator;
    private $fileSystem;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, Filesystem $fileSystem){

        $this->em = $em;
        $this->validator = $validator;
        $this->fileSystem = $fileSystem;
        
    }
    
    public function saveCitiesToDatabase($data = []){
        try{
            $batchSize = 20;

            for($i = 1; $i <= count($data); $i++){
                $city = new City();
                $city->setCityName($data[$i-1]['fields']['accentcity']);
                $city->setCountryCode($data[$i-1]['fields']['country']);
                $city->setLongitude($data[$i-1]['fields']['longitude']);
                $city->setLatitude($data[$i-1]['fields']['latitude']);
                $city->setPopulation($data[$i-1]['fields']['population']);

                $errors = $this->validator->validate($city);

                if (count($errors) > 0) {
                    continue;
                }

                $this->em->persist($city);

                if (($i % $batchSize) === 0) {
                    $this->em->flush();
                    $this->em->clear();
                }
            }
            $this->em->flush();
            $this->em->clear();
        } catch(\Exception $e){
            return 'Error: ' . $e->getMessage();
        }
    }

    public function deleteAllEntriesAndDumpJson($argument){
        try {
        $query = $this->em->createQuery(
            'SELECT c
            FROM AppBundle:City c'
        );
        
        $cities = $query->getArrayResult();
        
        if(empty($cities)){
            return 'Database is already empty';
        }
        $cities = json_encode($cities);

        if($argument === null){

            $this->fileSystem->dumpFile(sys_get_temp_dir() . '/' . 'data.json', $cities);
            $sql = 'TRUNCATE cities_api.city';

            $stmt = $this->em->getConnection()->query($sql);
            $stmt->execute();

            return 'Succefully dumped json and deleted database';
        }

        $this->fileSystem->dumpFile($argument . '/' . 'data.json', $cities);

            $sql = 'TRUNCATE cities_api.city';

            $stmt = $this->em->getConnection()->query($sql);
            $stmt->execute();

        return 'Succefully dumped json and deleted database';
        
        } catch (IOExceptionInterface $exception) {
            return "An error occurred while creating your directory at ".$exception->getPath() . " insert argument as: directory_name/sub_directory";
        } catch (\Exception $e){
            return 'Error: ' . $e->getMessage();
        }
        
    }

}