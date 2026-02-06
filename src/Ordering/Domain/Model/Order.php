<?php

# App\Ordering\Domain\Model\Order.php

declare(strict_types=1);

namespace App\Ordering\Domain\Model;

use App\Ordering\Domain\Exception\OrderCannotBeConfirmed;
use App\Ordering\Domain\ValueObject\Money;
use App\Ordering\Domain\ValueObject\Quantity;
use App\Ordering\Domain\ValueObject\Sku;

/**
 * Aggregate Root
 */
final class Order
{
    private string $status;

    /**
     * @var list<OrderItem>
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

        foreach ($this->items as $index => $item) {
            $sameSku = ($item->sku()->toString() === $sku->toString());
            $sameCurrency = ($item->unitPrice()->currency() === $unitPrice->currency());
            $samePrice = ($item->unitPrice()->cents() === $unitPrice->cents());

            // Merge only when key (SKU+PRICE+QTY) unique
            if ($sameSku && $sameCurrency && $samePrice) {
                $this->items[$index] = $item->withAddedQuantity($quantity);
                return;
            }
        }

        $this->items[] = new OrderItem($sku, $quantity, $unitPrice);
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

    /**
     * @return list<OrderItem>
     */
    public function items(): array
    {
        return $this->items;
    }
}
