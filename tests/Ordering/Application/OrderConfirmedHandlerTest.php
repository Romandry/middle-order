<?php

# App\Tests\Ordering\Application\OrderConfirmedHandlerTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Application;

use App\Ordering\Domain\Event\OrderConfirmed;
use App\Reporting\Application\Handler\OrderConfirmedHandler;
use App\Reporting\Application\Port\DailySalesRepository;
use App\Reporting\Application\Projection\DailySalesProjection;
use PHPUnit\Framework\TestCase;

final class OrderConfirmedHandlerTest extends TestCase
{
    public function test_it_increments_daily_sales_repository_on_event(): void
    {
        $repo = new class () implements DailySalesRepository {
            public int $calls = 0;

            /** @var array{day:\DateTimeImmutable, orders:int, revenue:int, currency:string}|null */
            public ?array $lastCall = null;

            public function incrementForToday(
                \DateTimeImmutable $day,
                int $ordersDelta,
                int $revenueCentsDelta,
                string $currency
            ): void {
                $this->calls++;
                $this->lastCall = [
                    'day' => $day,
                    'orders' => $ordersDelta,
                    'revenue' => $revenueCentsDelta,
                    'currency' => $currency,
                ];
            }
        };

        $handler = new OrderConfirmedHandler($repo);

        $handler(new OrderConfirmed());

        self::assertSame(1, $repo->calls, 'Repository must be called exactly once');
        self::assertNotNull($repo->lastCall);
        self::assertSame(1, $repo->lastCall['orders'], 'Increment confirmed order');
        self::assertSame(0, $repo->lastCall['revenue'], 'Revenue is 0 for this test');
        self::assertSame('EUR', $repo->lastCall['currency'], 'Currency is fixed to EUR for this test');

        $expedtedDay = (new \DateTimeImmutable())->format('Y-m-d');
        self::assertSame($expedtedDay, $repo->lastCall['day']->format('Y-m-d'));
    }
    //    public function test_it_updates_projection_on_event(): void
    //    {
    //        $projection = new DailySalesProjection();
    //        $handler = new OrderConfirmedHandler($projection);
    //
    //        $handler(new OrderConfirmed());
    //
    //        self::assertSame(1, $projection->confirmedOrders());
    //    }
}
