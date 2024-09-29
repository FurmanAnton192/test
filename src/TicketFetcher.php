<?php

namespace App;

use GuzzleHttp\Client;

class TicketFetcher
{
    private $client;
    private $apiUrl;
    private $apiKey;

    public function __construct($apiUrl, $apiKey)
    {
        $this->client = new Client();
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchTickets($page = 1, $perPage = 100)
    {
        $response = $this->client->request('GET', $this->apiUrl, [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}"
            ],
            'query' => [
                'page' => $page,
                'per_page' => $perPage
            ],
            'verify' => false
        ]);

        $tickets = json_decode($response->getBody(), true);

        return $tickets['data'] ?? [];
    }
}
