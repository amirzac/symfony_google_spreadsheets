<?php

declare(strict_types=1);

namespace App\Service\GoogleApiClient;

use Google_Service_Sheets;

class GoogleApiClientProvider
{
    private \Google_Client $googleApiClient;

    public function __construct(\Google_Client $googleApiClient, Config $config)
    {
        $this->googleApiClient = $googleApiClient;
        $this->googleApiClient->setApplicationName($config->getApplicationName());
        $this->googleApiClient->setAuthConfig($config->getCredentialFilePath());
    }

    public function sheets(): Google_Service_Sheets {
        $client = clone $this->googleApiClient;

        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        return new Google_Service_Sheets($client);
    }

    protected function getClient(): \Google_Client{
        return $this->googleApiClient;
    }
}