<?php

namespace App\DataTransferObjects;

class SnapshotValues
{
    public function __construct(
        public float $price,
        public array $full_response,
    ) {
    }
}
