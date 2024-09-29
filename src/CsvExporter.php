<?php

namespace App;

class CsvExporter
{
    private $file;

    public function __construct($filePath)
    {
        $this->file = fopen($filePath, 'w');
        $this->writeHeader();
    }

    private function writeHeader()
    {
        fputcsv($this->file, [
            'Ticket ID', 'Description', 'Status', 'Priority', 
            'Agent ID', 'Agent Name', 'Agent Email', 
            'Contact ID', 'Contact Name', 'Contact Email', 
            'Group ID', 'Group Name', 
            'Company ID', 'Company Name', 
            'Comments'
        ]);
    }

    public function writeTickets(array $tickets)
    {
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

    public function close()
    {
        fclose($this->file);
    }
}
