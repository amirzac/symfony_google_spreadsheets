<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\History;
use Symfony\Component\Serializer\SerializerInterface;

class HistoryJsonFileRepository
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function save(History $history): void
    {
        $data = $this->serializer->serialize($history->getItems(), 'json');

        $fp = fopen('data.json', 'w');
        fwrite($fp, $data);
        fclose($fp);
    }
}