<?php

declare(strict_types=1);

namespace ContaoThemeManager\Core\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ExcludeHeadlineStyleEvent extends Event
{
    public function __construct(
        public array $types,
    ) {
    }

    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    public function getTypes(): array
    {
        return $this->types;
    }
}
