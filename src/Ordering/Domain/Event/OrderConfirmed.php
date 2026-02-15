<?php

# App\Ordering\Domain\Event\OrderConfirmed.php

declare(strict_types=1);

namespace App\Ordering\Domain\Event;

use DateTimeImmutable;

final readonly class OrderConfirmed implements DomainEvent
{
    public function __construct(
        public string $orderId,
        public DateTimeImmutable $occurredAt,
        public int $revenueCents,
        public string $currency
    ) {
    }

    public function name(): string
    {
        return 'order.confirmed';
    }
}
