<?php

# App\Reporting\Application\Handler\OrderConfirmedHandler.php

declare(strict_types=1);

namespace App\Reporting\Application\Handler;

use App\Ordering\Domain\Event\OrderConfirmed;
use App\Reporting\Application\Projection\DailySalesProjection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderConfirmedHandler
{
    public function __construct(private DailySalesProjection $projection)
    {
    }

    public function __invoke(OrderConfirmed $event): void
    {
        $this->projection->incrementConfirmedOrders();
    }
}
