<?php

require __DIR__ . '/vendor/autoload.php';

use App\TicketFetcher;
use App\CsvExporter;
use App\TicketProcessor;

// Введіть дані API
$apiUrl = 'https://wunu5461.zendesk.com/api/v2/tickets.json';
$apiKey = '76cdJErhht0fAJSnje0bzdpdDIzkIkJ1fynT4S0x';
$filePath = 'tickets.csv';

// Створення об'єктів
$fetcher = new TicketFetcher($apiUrl, $apiKey);
$exporter = new CsvExporter($filePath);
$processor = new TicketProcessor($fetcher, $exporter, 10000, 100);

// Запуск процесу експорту
$processor->process();

echo "Tickets have been exported to $filePath\n";
