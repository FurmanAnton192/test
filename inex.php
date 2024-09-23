<?php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

class TicketFetcher {
    private $client;
    private $apiUrl;
    private $apiKey;

    public function __construct($apiUrl, $apiKey) {
        $this->client = new Client();
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchTickets($page = 1, $perPage = 100) {
        $response = $this->client->request('GET', $this->apiUrl, [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}"
            ],
            'query' => [
                'page' => $page,
                'per_page' => $perPage
            ]
        ]);

        $tickets = json_decode($response->getBody(), true);

        return $tickets['data'] ?? [];
    }
}

class CsvExporter {
    private $file;

    public function __construct($filePath) {
        $this->file = fopen($filePath, 'w');
        $this->writeHeader();
    }

    private function writeHeader() {
        fputcsv($this->file, [
            'Ticket ID', 'Description', 'Status', 'Priority', 
            'Agent ID', 'Agent Name', 'Agent Email', 
            'Contact ID', 'Contact Name', 'Contact Email', 
            'Group ID', 'Group Name', 
            'Company ID', 'Company Name', 
            'Comments'
        ]);
    }

    public function writeTickets($tickets) {
        foreach ($tickets as $ticket) {
            fputcsv($this->file, [
                $ticket['id'],
                $ticket['description'],
                $ticket['status'],
                $ticket['priority'],
                $ticket['agent']['id'] ?? '',
                $ticket['agent']['name'] ?? '',
                $ticket['agent']['email'] ?? '',
                $ticket['contact']['id'] ?? '',
                $ticket['contact']['name'] ?? '',
                $ticket['contact']['email'] ?? '',
                $ticket['group']['id'] ?? '',
                $ticket['group']['name'] ?? '',
                $ticket['company']['id'] ?? '',
                $ticket['company']['name'] ?? '',
                implode('; ', $ticket['comments'] ?? [])
            ]);
        }
    }

    public function close() {
        fclose($this->file);
    }
}

// Використання
$apiUrl = 'https://wunu4077.zendesk.com/agent/users/21764610469149/assigned_tickets';
$apiKey = 'twO66Tmb7KqL4Sr6rQ5zEJ0U7EyEp2Aujjur2s5j';
$filePath = 'tickets.csv';

$fetcher = new TicketFetcher($apiUrl, $apiKey);
$exporter = new CsvExporter($filePath);

$page = 1;
$perPage = 100;
$totalTickets = 10000; // умовна кількість

while ($page * $perPage <= $totalTickets) {
    $tickets = $fetcher->fetchTickets($page, $perPage);
    $exporter->writeTickets($tickets);
    $page++;
}

$exporter->close();

echo "Tickets have been exported to $filePath\n";
