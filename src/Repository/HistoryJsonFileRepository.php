<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\History;
use Symfony\Component\Serializer\SerializerInterface;

class HistoryJsonFileRepository
{
    private const FILENAME = 'data.json';

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function save(History $history): void
    {
        $data = $this->serializer->serialize($history->getItems(), 'json');

        $fp = fopen(self::FILENAME, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }

    public function get(): History
    {
        if(!file_exists(self::FILENAME)) {
            throw new \LogicException(sprintf("File %s doesn't exist",self::FILENAME));
        }
        $data = json_decode(file_get_contents(self::FILENAME));
        $history = new History();
        foreach ($data as $item) {
            $history->addItem($item);
        }

        return $history;
    }
}