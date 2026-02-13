<?php

# App\Tests\Integration\ReportingDailySalesRepositoryTest.php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Reporting\Infrastructure\Persistence\PostgresDailySalesRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ReportingDailySalesRepositoryTest extends KernelTestCase
{
    public function test_it_upserts_daily_sales_rows(): void
    {
        self::bootKernel();

        /** @var Connection $db */
        $db = self::getContainer()->get(Connection::class);

        $db->executeStatement('TRUNCATE report_daily_sales');

        $repo = new PostgresDailySalesRepository($db);

        $day = new \DateTimeImmutable('2026-02-13');
        $repo->incrementForToday($day, 1, 500, 'EUR');
        $repo->incrementForToday($day, 2, 700, 'EUR');

        $row = $db->fetchAssociative(
            'SELECT confirmed_orders, revenue_cents, currency FROM report_daily_sales WHERE day = :day',
            ['day' => '2026-02-13']
        );

        self::assertNotFalse($row);
        self::assertSame(3, (int) $row['confirmed_orders']);
        self::assertSame(1200, (int) $row['revenue_cents']);
        self::assertSame('EUR', $row['currency']);
    }
}
