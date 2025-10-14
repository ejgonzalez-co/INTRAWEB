<?php

namespace Modules\Leca\Http\Services;

use Google\Client;
use Google\Services\Sheets;
use Google\Services\ValueRange;


class GoogleSheetsServices
{

    public $client, $service, $documentId, $range;

    public function  __construct()
    {

        $this->client = $this->getClient();
        $this->service= new Sheets($this->client);
        $this->documentId='1WYWGCwtUL8-CZ2gocoJaj_Fk7mHV0tYQp9a_jztaDHQ';
        $this->range='A:Z';

    }

    /**
     * Returns an authorized API client.
     * @return Client the authorized client object
     */
    public function getClient()
    {
        $client = new Client();
        $client->setApplicationName('prueba');
        $client->setRedirectUri('http://localhost:8080/googlesheet');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig('credenciales.json');
        $client->setAccessType('offline');

        return $client;
    }

    public function readSheet()
    {
        $doc= $this->service->readsheet_values->get( $this->documentId, $this->range);
        return $doc;
    }

    public function writeSheet($values){

        $body = new ValueRange([
            'values' => $values,
        ]);

        $params=[
            'valueInputOption' => 'RAW',
        ];

        $result= $this->service->spreadsheets_values->update($this->documentId, $this->range, $body, $params);

    }


}
