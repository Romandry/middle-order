<?php

# App\Ordering\Domain\ValueObject\Quantity.php

declare(strict_types=1);

namespace App\Ordering\Domain\ValueObject;

use App\Ordering\Domain\Exception\InvalidQuantity;

final readonly class Quantity
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new InvalidQuantity('Quantity must be greater than 0');
        }
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
