<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class ExternalApiConnectionService
{
    public function getDecodeResponseByUriAndEndpoint(string $baseUri, string $endpoint): array
    {
        $response = $this->prepareConnectionAndResponse($baseUri, $endpoint);

        if (!$response instanceof ResponseInterface) {
            return $response;
        }

        $body = $response->getBody();
        $decodeResponse = json_decode((string) $body);

        return $decodeResponse[0]->rates;
    }

    private function prepareConnectionAndResponse(string $baseUri, string $endpoint): array|ResponseInterface
    {
        // Create a client with a base URI
        $apiClient = new Client([
            'base_uri' => $baseUri,
        ]);

        // Send a request to $baseUri.$endpoint
        try {
            $response = $apiClient->request('GET', $endpoint);
        } catch (GuzzleException $e) {
            return [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }

        return $response;
    }
}