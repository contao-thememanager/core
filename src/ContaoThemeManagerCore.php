<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoThemeManagerCore extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
