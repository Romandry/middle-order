<?php

# App\Reporting\Application\Handler\OrderConfirmedHandler.php

declare(strict_types=1);

namespace App\Reporting\Application\Handler;

use App\Ordering\Domain\Event\OrderConfirmed;
use App\Reporting\Application\Port\DailySalesRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderConfirmedHandler
{
    public function __construct(private DailySalesRepository $repository)
    {
    }

    public function __invoke(OrderConfirmed $event): void
    {
        $today = new \DateTimeImmutable('today');

        $this->repository->incrementForToday($today, 1, 0, 'EUR');
    }
}
