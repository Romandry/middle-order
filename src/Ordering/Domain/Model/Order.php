<?php

# App\Ordering\Domain\Model\Order.php

declare(strict_types=1);

namespace App\Ordering\Domain\Model;

use App\Ordering\Domain\Exception\OrderCannotBeConfirmed;
use App\Ordering\Domain\ValueObject\Money;
use App\Ordering\Domain\ValueObject\Quantity;
use App\Ordering\Domain\ValueObject\Sku;

final class Order
{
    private string $status;

    /**
     * @var list<array{sku: Sku, quantity: Quantity, unitPrice: Money}>
     */
    private array $items = [];

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function draft(): self
    {
        return new self('draft');
    }

    public function addItem(Sku $sku, Quantity $quantity, Money $unitPrice): void
    {
        if ($this->status !== 'draft') {
            throw new \App\Ordering\Domain\Exception\OrderIsLocked('Order cannot be modified after confirmation');
        }

        $this->items[] = [
            'sku'       => $sku,
            'quantity'  => $quantity,
            'unitPrice' => $unitPrice,
        ];
    }

    public function confirm(): void
    {
        if ($this->items === []) {
            throw new OrderCannotBeConfirmed('Order cannot be confirmed without items');
        }

        $this->status = 'confirmed';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }
}
