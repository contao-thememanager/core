<?php

namespace ContaoThemeManager\Core\Twig;

use Contao\StringUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ThemeManagerExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('tm_deserialize', [$this, 'deserialize'])
        ];
    }

    public function deserialize(string $value): array
    {
        return StringUtil::deserialize($value, true);
    }
}
