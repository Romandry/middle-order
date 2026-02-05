<?php

# App\Tests\Ordering\Domain\MoneyTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Domain;

use App\Ordering\Domain\Exception\InvalidMoney;
use App\Ordering\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function test_it_rejects_negative_amount(): void
    {
        $this->expectException(InvalidMoney::class);
        Money::fromCents(-1, 'EUR');
    }

    public function test_it_creates_money_form_cents(): void
    {
        $money = Money::fromCents(1099, 'EUR');

        self::assertSame(1099, $money->cents());
        self::assertSame('EUR', $money->currency());
    }

    public function test_it_adds_money_of_same_currency(): void
    {
        $a = Money::fromCents(350, 'EUR');
        $b = Money::fromCents(250, 'EUR');

        $sum = $a->add($b);

        self::assertSame(600, $sum->cents());
        self::assertSame('EUR', $a->currency());
    }

    public function test_it_rejects_addition_of_different_currencies(): void
    {
        $a = Money::fromCents(350, 'EUR');
        $b = Money::fromCents(250, 'USD');

        $this->expectException(InvalidMoney::class);
        $a->add($b);
    }

    public function test_it_multiplies_money_by_positive_quantity(): void
    {
        $price = Money::fromCents(199, 'EUR');
        $total = $price->multiply(3);

        self::assertSame(597, $total->cents());
    }
}
