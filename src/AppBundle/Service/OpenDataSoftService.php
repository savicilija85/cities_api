<?php

namespace AppBundle\Service;

use GuzzleHttp\ClientInterface;
use AppBundle\Service\CityService;

class OpenDataSoftService {

    private $API_URL = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=worldcitiespop&sort=population&rows=";
    private $client;
    private $cityService;

    public function __construct(ClientInterface $client, CityService $cityService){

        $this->client = $client;
        $this->cityService = $cityService;

    }

    public function getCities($argument){
        if($argument === null){
            $argument = 100;
            $response = $this->client->request('GET', $this->API_URL . $argument);
            $data = json_decode($response->getBody(), true);
            $this->cityService->saveCitiesToDatabase($data['records']);

            return $argument . " entries created in database";
        } elseif(!is_numeric($argument)){

            return "Argument must be integer type";
        
        } else {
            $response = $this->client->request('GET', $this->API_URL . $argument);
            $data = json_decode($response->getBody(), true);
            $this->cityService->saveCitiesToDatabase($data['records']);

            return $argument . " entries created in database";
        }
    }

}