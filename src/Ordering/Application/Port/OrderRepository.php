<?php

# App\Ordering\Application\Port\OrderRepository.php

declare(strict_types=1);

namespace App\Ordering\Application\Port;

use App\Ordering\Domain\Model\Order;

interface OrderRepository
{
    public function get(string $orderId): ?Order;
    public function save(Order $order): void;
}
