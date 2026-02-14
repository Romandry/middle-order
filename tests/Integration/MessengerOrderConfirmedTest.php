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
        $bus->dispatch(new OrderConfirmed());

        $today = (new \DateTimeImmutable())->format('Y-m-d');

        $row = $db->fetchAssociative(
            'SELECT confirmed_orders, revenue_cents, currency FROM report_daily_sales WHERE day = :day',
            ['day' => $today]
        );

        self::assertNotFalse($row);
        self::assertSame(1, (int) $row['confirmed_orders']);
        self::assertSame(0, (int) $row['revenue_cents']);
        self::assertSame('EUR', $row['currency']);
    }
}
