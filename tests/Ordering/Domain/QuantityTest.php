<?php

# App\Tests\Ordering\Domain\QuantityTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Domain;

use App\Ordering\Domain\Exception\InvalidQuantity;
use App\Ordering\Domain\ValueObject\Quantity;
use PHPUnit\Framework\TestCase;

final class QuantityTest extends TestCase
{
    public function test_it_rejects_zero_or_negative_quantity(): void
    {
        $this->expectException(InvalidQuantity::class);
        new Quantity(0);
    }

    public function test_it_allows_positive_quantity(): void
    {
        $qty = new Quantity(2);
        self::assertSame(2, $qty->toInt());
    }
}
