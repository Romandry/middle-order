<?php

# App\Tests\Integration\MessengerOrderConfirmedTest.php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Ordering\Domain\Event\OrderConfirmed;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerOrderConfirmedTest extends KernelTestCase
{
    public function test_dispatching_event_calls_handler_and_updates_daily_sales_table(): void
    {
        self::bootKernel();

        /** @var Connection $db */
        $db = self::getContainer()->get(Connection::class);

        $db->executeStatement('TRUNCATE report_daily_sales');

        /** @var MessageBusInterface $bus */
        $bus = self::getContainer()->get(MessageBusInterface::class);

        $occuredAt = new \DateTimeImmutable('2026-02-13 00:00:00');
        $bus->dispatch(new OrderConfirmed(
            orderId: 'ORDER-123',
            occurredAt: $occuredAt,
            revenueCents: 500,
            currency: 'EUR'
        ));

        $row = $db->fetchAssociative(
            'SELECT confirmed_orders, revenue_cents, currency, day FROM report_daily_sales WHERE day = :day',
            ['day' => $occuredAt->format('Y-m-d')]
        );

        self::assertNotFalse($row);
        self::assertSame(1, (int) $row['confirmed_orders']);
        self::assertSame(500, (int) $row['revenue_cents']);
        self::assertSame('EUR', $row['currency']);
        self::assertSame('2026-02-13', $row['day']);
    }
}
