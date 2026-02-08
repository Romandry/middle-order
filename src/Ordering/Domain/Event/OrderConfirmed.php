<?php

# App\Ordering\Domain\Event\OrderConfirmed.php

declare(strict_types=1);

namespace App\Ordering\Domain\Event;

final readonly class OrderConfirmed implements DomainEvent
{
    public function name(): string
    {
        return 'order.confirmed';
    }
}
