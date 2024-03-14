<?php

namespace ContaoThemeManager\Core\Twig;

use Contao\StringUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ThemeManagerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        // ToDo: change to deserialize and add legacy wrapper
        return [
            new TwigFilter('tm_deserialize', [$this, 'deserialize'])
        ];
    }

    public function deserialize(string $value): array
    {
        return StringUtil::deserialize($value, true);
    }
}
