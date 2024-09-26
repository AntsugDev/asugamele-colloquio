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
     *
     * @OA\Get (
     *       path="/api/list",
     *               summary="Proxy api",
     *               description="Proxy api https://api.openbrewerydb.org/v1/breweries",
     *               tags={"Table"},
     *       security={{"bearerAuth":{}}},
     *  @OA\Response(
     *                    response=200,
     *                    description="Ritorna la lista dell'api in get",
     *
     *                ),
     *   )
     *
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
