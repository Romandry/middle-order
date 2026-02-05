<?php

# App\Tests\Ordering\Domain\SkuTest.php

declare(strict_types=1);

namespace App\Tests\Ordering\Domain;

use App\Ordering\Domain\Exception\InvalidSku;
use App\Ordering\Domain\ValueObject\Sku;
use PHPUnit\Framework\TestCase;

final class SkuTest extends TestCase
{
    public function test_it_rejects_empty_sku(): void
    {
        $this->expectException(InvalidSku::class);
        new Sku('');
    }

    public function test_it_rejects_invalid_sku_format(): void
    {
        $this->expectException(InvalidSku::class);
        new Sku('ABC-1');
    }

    public function test_it_normalized_sku_format(): void
    {
        $sku = new Sku('    clip-123     ');

        self::assertSame('CLIP-123', $sku->toString());
    }

    public function test_it_accepts_valid_sku(): void
    {
        $sku = new Sku('CLIP-123');
        self::assertSame('CLIP-123', $sku->toString());
    }
}
