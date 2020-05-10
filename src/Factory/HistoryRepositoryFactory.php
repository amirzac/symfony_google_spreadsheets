<?php

declare(strict_types=1);

namespace App\Factory;

use App\Repository\HistoryJsonFileRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HistoryRepositoryFactory
{
    public function createJsonFileRepository(): HistoryJsonFileRepository{
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return new HistoryJsonFileRepository($serializer);
    }
}