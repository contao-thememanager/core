<?php

namespace ContaoThemeManager\Core\EventListener\Page;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\PageRegular;
use Contao\LayoutModel;
use Contao\PageModel;

#[AsHook('generatePage')]
class GeneratePageListener
{
    public function __invoke(PageModel $pageModel, LayoutModel $layout, PageRegular $pageRegular): void
    {
        $GLOBALS['TL_HEAD'][] = '<meta name="generator" content="Contao ThemeManager">';
    }
}
