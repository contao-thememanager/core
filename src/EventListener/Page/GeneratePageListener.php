<?php

declare(strict_types=1);

namespace ContaoThemeManager\Core\EventListener\Page;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;

#[AsHook('generatePage')]
class GeneratePageListener
{
    public function __invoke(PageModel $pageModel, LayoutModel $layout, PageRegular $pageRegular): void
    {
        $GLOBALS['TL_HEAD'][] = '<meta name="generator" content="Contao ThemeManager">';
    }
}
