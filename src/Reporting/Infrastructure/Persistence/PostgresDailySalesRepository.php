<?php

# App\Reporting\Infrastructure\Persistence\PostgresDailySalesRepository.php

declare(strict_types=1);

namespace App\Reporting\Infrastructure\Persistence;

use App\Reporting\Application\Port\DailySalesRepository;
use Doctrine\DBAL\Connection;

final class PostgresDailySalesRepository implements DailySalesRepository
{
    public function __construct(private Connection $db)
    {
    }
    public function incrementForToday(
        \DateTimeImmutable $day,
        int $ordersDelta,
        int $revenueCentsDelta,
        string $currency
    ): void {
        $dayStr = $day->format('Y-m-d');

        $sql = <<<SQL
            INSERT INTO report_daily_sales (day, confirmed_orders, revenue_cents, currency)
            VALUES (:day, :orders, :revenue, :currency)
            ON CONFLICT (day)
            DO UPDATE SET
              confirmed_orders = report_daily_sales.confirmed_orders + EXCLUDED.confirmed_orders,
              revenue_cents = report_daily_sales.revenue_cents + EXCLUDED.revenue_cents
            SQL;

        $this->db->executeStatement($sql, [
            'day' => $dayStr,
            'orders' => $ordersDelta,
            'revenue' => $revenueCentsDelta,
            'currency' => $currency,
        ]);
    }
}
