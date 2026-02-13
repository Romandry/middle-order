<?php

# App\Tests\Ordering\Application\ConfirmOrderHandlerTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Application;

use App\Ordering\Application\Handler\ConfirmOrderHandler;
use App\Ordering\Application\Port\OrderRepository;
use App\Ordering\Domain\Event\OrderConfirmed;
use App\Ordering\Domain\Model\Order;
use App\Ordering\Domain\ValueObject\Money;
use App\Ordering\Domain\ValueObject\Quantity;
use App\Ordering\Domain\ValueObject\Sku;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class ConfirmOrderHandlerTest extends TestCase
{
    //    public function test_it_dispatches_domain_events_after_confirm(): void
    //    {
    //        $order = Order::draft();
    //
    //        $order->addItem(
    //            new Sku('CLIP-123'),
    //            new Quantity(2),
    //            Money::fromCents(100, 'EUR')
    //        );
    //
    //        $bus = new class () implements MessageBusInterface {
    //            /**
    //             * @var list<object>
    //             */
    //            public array $dispatchedMessages = [];
    //
    //            /**
    //             * @param StampInterface[] $stamps
    //             */
    //            public function dispatch(object $message, array $stamps = []): Envelope
    //            {
    //                $msgObject = $message instanceof Envelope ? $message->getMessage() : $message;
    //
    //                $this->dispatchedMessages[] = $msgObject;
    //                return $message instanceof Envelope ? $message : new Envelope($message);
    //            }
    //        };
    //
    //        $handler = new ConfirmOrderHandler($bus);
    //        $handler->handle($order);
    //
    //        self::assertCount(1, $bus->dispatchedMessages);
    //        self::assertInstanceOf(OrderConfirmed::class, $bus->dispatchedMessages[0]);
    //    }

    public function test_it_loads_order_confirms_it_saves_and_dispathes_event(): void
    {
        $order = Order::draft();
        $order->addItem(
            new Sku('CLIP-123'),
            new Quantity(2),
            Money::fromCents(100, 'EUR')
        );

        $repo = new class ($order) implements OrderRepository {
            private ?Order $order;
            public bool $saved = false;

            public function __construct(?Order $order)
            {
                $this->order = $order;
            }

            public function get(string $orderId): ?Order
            {
                return $this->order;
            }

            public function save(Order $order): void
            {
                $this->order = $order;
                $this->saved = true;
            }
        };
        $bus = new class () implements MessageBusInterface {
            /**
             * @var list<object>
             */
            public array $dispatchedMessages = [];

            public function dispatch(object $message, array $stamps = []): Envelope
            {
                $msgObject = $message instanceof Envelope ? $message->getMessage() : $message;

                $this->dispatchedMessages[] = $msgObject;
                return $message instanceof Envelope ? $message : new Envelope($message);
            }
        };

        $handler = new ConfirmOrderHandler($repo, $bus);
        $handler->handle('ORDER-111');

        self::assertTrue($repo->saved, 'Order should have been saved after confirmation');
        self::assertCount(1, $bus->dispatchedMessages);
        self::assertInstanceOf(OrderConfirmed::class, $bus->dispatchedMessages[0]);
    }
}
