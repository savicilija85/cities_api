<?php

namespace AppBundle\Service;

use AppBundle\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CityService{

    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator){

        $this->em = $em;
        $this->validator = $validator;
        
    }
    
    public function saveCitiesToDatabase($data = []){

        //$em = $this->getDoctrine()->getManager();

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
        
    }

}