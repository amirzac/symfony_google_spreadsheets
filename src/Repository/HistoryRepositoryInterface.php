<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\History;

interface HistoryRepositoryInterface
{
    public function save(History $history): void;
}