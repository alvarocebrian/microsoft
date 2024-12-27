<?php

/*
 * This file is part of the SDA package
 *
 * Copyright (c) 2020-2024 STRONGHOLD ASSET MANAGEMENT
 * All right reserved
 *
 * @author Álvaro Cebrián <acebrian@strongholdam.com>
 * @author Daniel González <dgonzalez@strongholdam.com>
 * @author Raúl Callado <rcallado@strongholdam.com>
 */

namespace Strongholdam\Microsoft\GraphApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class MicrosoftApiService
{
    private const BASE_GRAPH_URL = 'https://graph.microsoft.com';
    private const BASE_TOKEN_URL = 'https://login.microsoftonline.com/{tenant-id}/oauth2/v2.0/token';

    public function __construct(private HttpClientInterface $client)
    {
    }

    public function call(string $path, string $method, array $parameters): ResponseInterface
    {
        $url = sprintf('%s%s', self::BASE_GRAPH_URL, $path);

        $parameters = array_merge([
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->getAccessToken()),
            ],
        ], $parameters);

        return $this->client->request($method, $url, $parameters);
    }

    /** Implement here the logic to retrieve the access token */
    abstract protected function getAccessToken(): string;

    /**
     * Retrieve a new access token and refresh token.
     *
     * @return array {
     *               access_token: Contains the access token to make requests to Graph Api,
     *               refresh_token: New refresh token to ask for a new access token
     *               }
     */
    public function refreshAccessToken(Credentials $credentials, array $scope): array
    {
        $url = str_replace('{tenant-id}', $credentials->TENANT_ID, self::BASE_TOKEN_URL);

        $response = $this->client->request('POST', $url, [
            'body' => [
                'client_id' => $credentials->CLIENT_ID,
                'scope' => implode(' ', $scope),
                'refresh_token' => $credentials->REFRESH_TOKEN,
                'grant_type' => 'refresh_token',
                'client_secret' => $credentials->CLIENT_SECRET,
            ],
        ]);

        if (200 != $response->getStatusCode()) { // NOT OK response
            throw new \Exception('Error updating credentials');
        }

        $bodyResponse = $response->getContent();
        $bodyResponse = json_decode($bodyResponse, true);

        return [
            'access_token' => $bodyResponse['access_token'],
            'refresh_token' => $bodyResponse['refresh_token'],
        ];
    }
}
