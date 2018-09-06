<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\City;
use Symfony\Component\HttpFoundation\JsonResponse;

class CityController extends Controller{

    /**
     * @Route("/api/cities")
     * @Method({"GET"})
     */
    public function showCities(){
        $cities = $this->getDoctrine()->getRepository(City::class)->findAll();
        
        if(empty($cities)){
            $response = [
                'message' => 'No cities found!',
                'errors' => null,
                'result' => null
            ];

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($cities, 'json');

        $response = [
            'message' => 'Success',
            'errors' => null,
            'result' => json_decode($data)
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/api/cities/{id}")
     * @Method({"GET"})
     */
    public function getCity($id){
        $city = $this->getDoctrine()->getRepository(City::class)->find($id);

        if(empty($city)){
            $response = [
                'message' => 'No city found!',
                'errors' => null,
                'result' => null
            ];

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($city, 'json');

        $response = [
            'message' => 'Success',
            'errors' => null,
            'result' => json_decode($data)
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/api/cities/{id}")
     * @Method({"DELETE"})
     */
    public function deleteCity($id){
        $city = $this->getDoctrine()->getRepository(City::class)->find($id);

        if(empty($city)){
            $response = [
                'message' => 'No city found!',
                'errors' => null,
                'result' => null
            ];

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $em=$this->getDoctrine()->getManager();
        $em->remove($city);
        $em->flush();

        $response = [
            'message' => 'City deleted!',
            'errors' => null,
            'result' => null
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }


}
