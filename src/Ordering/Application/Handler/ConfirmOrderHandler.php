<?php

# App\Ordering\Application\Handler\ConfirmOrderHandler.php

declare(strict_types=1);

namespace App\Ordering\Application\Handler;

use App\Ordering\Application\Port\OrderRepository;
use Symfony\Component\Messenger\MessageBusInterface;

final class ConfirmOrderHandler
{
    public function __construct(
        private OrderRepository $orders,
        private MessageBusInterface $bus
    ) {
    }

    public function handle(string $orderId): void
    {
        $order = $this->orders->get($orderId);

        if (null === $order) {
            throw new \RuntimeException('Order not found: ' . $orderId);
        }

        $order->confirm();

        $this->orders->save($order);

        foreach ($order->pullDomainEvents() as $event) {
            $this->bus->dispatch($event);
        }
    }
}
