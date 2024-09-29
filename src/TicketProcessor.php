<?php

namespace App;

class TicketProcessor
{
    private $fetcher;
    private $exporter;
    private $totalTickets;
    private $perPage;

    public function __construct(TicketFetcher $fetcher, CsvExporter $exporter, $totalTickets = 10000, $perPage = 100)
    {
        $this->fetcher = $fetcher;
        $this->exporter = $exporter;
        $this->totalTickets = $totalTickets;
        $this->perPage = $perPage;
    }

    public function process()
    {
        $page = 1;

        while ($page * $this->perPage <= $this->totalTickets) {
            $tickets = $this->fetcher->fetchTickets($page, $this->perPage);
            if (empty($tickets)) {
                break;
            }
            $this->exporter->writeTickets($tickets);
            $page++;
        }

        $this->exporter->close();
    }
}
