<?php

# App\Tests\Integration\MessengerOrderConfirmedTest.php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Ordering\Domain\Event\OrderConfirmed;
use App\Reporting\Application\Projection\DailySalesProjection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerOrderConfirmedTest extends KernelTestCase
{
    public function test_dispatching_event_calls_handler_and_updates_projection(): void
    {
        self::bootKernel();

        $bus = self::getContainer()->get(MessageBusInterface::class);
        $projection = self::getContainer()->get(DailySalesProjection::class);
        self::assertInstanceOf(DailySalesProjection::class, $projection);

        self::assertSame(0, $projection->confirmedOrders());
        $bus->dispatch(new OrderConfirmed());
        self::assertSame(1, $projection->confirmedOrders());
    }
}
