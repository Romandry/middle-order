<?php

# App\Ordering\Domain\ValueObject\Sku.php

declare(strict_types=1);

namespace App\Ordering\Domain\ValueObject;

use App\Ordering\Domain\Exception\InvalidSku;

final readonly class Sku
{
    private string $sku;
    public function __construct(
        string $sku
    ) {
        $normalized = strtoupper(trim($sku));

        if ($normalized === '') {
            throw new InvalidSku('SKU cannot be empty');
        }

        if (!preg_match('/^CLIP-\d+$/', $normalized)) {
            throw new InvalidSku('SKU must match format (CLIP-<digits>)');
        }
        $this->sku = $normalized;
    }

    public function toString(): string
    {
        return $this->sku;
    }
}
