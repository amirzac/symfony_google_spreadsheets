<?php

declare(strict_types=1);

namespace App\Model;

class History
{
    private array $items = [];

    public function getItems(): array {
        return $this->items;
    }

    public function addItem(array $item): void
    {
        if($item[0]) {
            $this->items[] = $item;
        }

    }
}