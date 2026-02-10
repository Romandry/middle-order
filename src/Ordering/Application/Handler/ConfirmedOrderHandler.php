<?php

# App\Ordering\Application\Handler\ConfirmedOrderHandler.php

declare(strict_types=1);

namespace App\Ordering\Application\Handler;

use App\Ordering\Domain\Model\Order;
use Symfony\Component\Messenger\MessageBusInterface;

final class ConfirmedOrderHandler
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function handle(Order $order): void
    {
        $order->confirm();
        $events = $order->pullDomainEvents();

        foreach ($events as $event) {
            $this->bus->dispatch($event);
        }
    }
}
