<?php

declare(strict_types=1);

namespace App\Service\GoogleApiClient;

class Factory
{
    public function createClientProvider(string $credentialFilePath, string $applicationName): GoogleApiClientProvider
    {
        return new GoogleApiClientProvider(new \Google_Client(), new Config($credentialFilePath, $applicationName));
    }
}