<?php

namespace App\Http\Api\ApiController;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    /**
     * @throws \Exception
     */
    public function get_list()
    {

        try{
            $client = new Client();
            $response = $client->request('GET', env('API_TESTER'));
            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody()->getContents(), true);
            return new JsonResponse($content, $statusCode);
        }catch (\Exception|GuzzleException|ClientException $e){
            throw new \Exception($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
