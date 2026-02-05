<?php

# App\Ordering\Domain\ValueObject\Money.php

declare(strict_types=1);

namespace App\Ordering\Domain\ValueObject;

use App\Ordering\Domain\Exception\InvalidMoney;

final readonly class Money
{
    private function __construct(
        private int $cents,
        private string $currency
    ) {
        if ($cents < 0) {
            throw new InvalidMoney('Money amount cannot be negative');
        }

        $normalized = strtoupper(trim($currency));
        if ($normalized === '' || strlen($normalized) !== 3) {
            throw new InvalidMoney('Currency must be a 3-letter ISO code');
        }
    }

    public static function fromCents(int $cents, string $currency): self
    {
        $normalized = strtoupper(trim($currency));
        return new self($cents, $normalized);
    }

    public function add(self $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidMoney('Currency must be equal to other currency');
        }
        return new self($this->cents + $other->cents, $this->currency);
    }

    public function multiply(int $multiplier): self
    {
        if ($multiplier <= 0) {
            throw new InvalidMoney('Multiplier must be greater than 0');
        }
        return new self($this->cents * $multiplier, $this->currency);
    }

    public function cents(): int
    {
        return $this->cents;
    }
    public function currency(): string
    {
        return $this->currency;
    }
}
