<?php

# App\Reporting\Application\Port\DailySalesRepository.php

declare(strict_types=1);

namespace App\Reporting\Application\Port;

use DateTimeImmutable;

interface DailySalesRepository
{
    public function incrementForToday(
        DateTimeImmutable $day,
        int $ordersDelta,
        int $revenueCentsDelta,
        string $currency
    ): void;
}
