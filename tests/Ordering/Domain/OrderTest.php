<?php

# App\Tests\Ordering\Domain\OrderTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Domain;

use App\Ordering\Domain\Exception\OrderCannotBeConfirmed;
use App\Ordering\Domain\Exception\OrderIsLocked;
use App\Ordering\Domain\Model\Order;
use App\Ordering\Domain\ValueObject\Money;
use App\Ordering\Domain\ValueObject\Quantity;
use App\Ordering\Domain\ValueObject\Sku;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function test_it_cannot_be_confirmed_when_empty(): void
    {
        $order = Order::draft();

        $this->expectException(OrderCannotBeConfirmed::class);
        $order->confirm();
    }

    public function test_it_can_be_confirmed_when_has_items(): void
    {
        $order = Order::draft();

        $order->addItem(
            new Sku('CLIP-123'),
            new Quantity(2),
            Money::fromCents(200, 'EUR')
        );
        $order->confirm();

        self::assertTrue($order->isConfirmed());
    }

    public function test_it_cannot_be_modified_after_confirmation(): void
    {
        $order = Order::draft();

        $order->addItem(
            new Sku('CLIP-123'),
            new Quantity(2),
            Money::fromCents(200, 'EUR')
        );
        $order->confirm();

        $this->expectException(OrderIsLocked::class);

        $order->addItem(
            new Sku('CLIP-999'),
            new Quantity(1),
            Money::fromCents(100, 'EUR')
        );
    }
}
