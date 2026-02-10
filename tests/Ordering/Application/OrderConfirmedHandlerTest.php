<?php

# App\Tests\Ordering\Application\OrderConfirmedHandlerTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Application;

use App\Ordering\Domain\Event\OrderConfirmed;
use App\Reporting\Application\Handler\OrderConfirmedHandler;
use App\Reporting\Application\Projection\DailySalesProjection;
use PHPUnit\Framework\TestCase;

final class OrderConfirmedHandlerTest extends TestCase
{
    public function test_it_updates_projection_on_event(): void
    {
        $projection = new DailySalesProjection();
        $handler = new OrderConfirmedHandler($projection);

        $handler(new OrderConfirmed());

        self::assertSame(1, $projection->confirmedOrders());
    }
}
