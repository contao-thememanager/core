<?php

namespace ContaoThemeManager\Core\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ExcludeSecondHeadlineEvent extends Event
{
    public function __construct(public array $types)
    {}

    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    public function getTypes(): array
    {
        return $this->types;
    }
}
