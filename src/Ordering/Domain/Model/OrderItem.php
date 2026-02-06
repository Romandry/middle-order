<?php

# App\Ordering\Domain\Model\OrderItem.php

declare(strict_types=1);

namespace App\Ordering\Domain\Model;

use App\Ordering\Domain\ValueObject\Money;
use App\Ordering\Domain\ValueObject\Quantity;
use App\Ordering\Domain\ValueObject\Sku;

final readonly class OrderItem
{
    public function __construct(
        private Sku $sku,
        private Quantity $quantity,
        private Money $unitPrice
    ) {

    }

    public function withAddedQuantity(Quantity $quantityAdd): self
    {
        $newQty = new Quantity($this->quantity->toInt() + $quantityAdd->toInt());
        return new self($this->sku, $newQty, $this->unitPrice);
    }

    public function sku(): Sku
    {
        return $this->sku;
    }
    public function quantity(): Quantity
    {
        return $this->quantity;
    }
    public function unitPrice(): Money
    {
        return $this->unitPrice;
    }
}
