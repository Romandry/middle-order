<?php

# App\Tests\Ordering\Domain\OrderDomainEventTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Domain;

use App\Ordering\Domain\Model\Order;
use App\Ordering\Domain\ValueObject\Money;
use App\Ordering\Domain\ValueObject\Quantity;
use App\Ordering\Domain\ValueObject\Sku;
use PHPUnit\Framework\TestCase;

final class OrderDomainEventTest extends TestCase
{
    public function test_it_emits_order_confirmed_event_on_confirm(): void
    {
        $order = Order::draft();

        $order->addItem(
            new Sku('CLIP-123'),
            new Quantity(1),
            Money::fromCents(100, 'EUR')
        );

        $order->confirm();

        $events = $order->pullDomainEvents();

        self::assertCount(1, $events);
        self::assertSame('order.confirmed', $events[0]->name());
    }

    public function test_it_clears_events_after_pulling(): void
    {
        $order = Order::draft();

        $order->addItem(
            new Sku('CLIP-123'),
            new Quantity(1),
            Money::fromCents(100, 'EUR')
        );

        $order->confirm();

        $order->pullDomainEvents();
        self::assertCount(0, $order->pullDomainEvents());
    }
}
