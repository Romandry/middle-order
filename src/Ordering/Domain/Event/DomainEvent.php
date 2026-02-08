<?php

# App\Ordering\Domain\Event\DomainEvent.php

declare(strict_types=1);

namespace App\Ordering\Domain\Event;

interface DomainEvent
{
    public function name(): string;
}
