<?php

# App\Reporting\Application\Projection\DailySalesProjection.php

declare(strict_types=1);

namespace App\Reporting\Application\Projection;

final class DailySalesProjection
{
    private int $confirmedOrders = 0;

    public function incrementConfirmedOrders(): void
    {
        $this->confirmedOrders++;
    }
    public function confirmedOrders(): int
    {
        return $this->confirmedOrders;
    }
}
