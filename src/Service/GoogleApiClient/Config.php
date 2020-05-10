<?php

declare(strict_types=1);

namespace App\Service\GoogleApiClient;

class Config
{
    private string $credentialFilePath;

    private string $applicationName;

    public function __construct(string $credentialFilePath, string $applicationName)
    {
        $this->credentialFilePath = $credentialFilePath;
        $this->applicationName = $applicationName;
    }

    public function getCredentialFilePath(): string
    {
        return $this->credentialFilePath;
    }

    public function getApplicationName(): string
    {
        return $this->applicationName;
    }
}