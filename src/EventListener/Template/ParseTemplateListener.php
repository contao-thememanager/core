<?php

declare(strict_types=1);

namespace ContaoThemeManager\Core\EventListener\Template;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\StringUtil;
use Contao\Template;

/**
 * @deprecated as of CTM 2.1, to be removed in a future version
 */
#[AsHook('parseTemplate')]
class ParseTemplateListener
{
    public function __invoke(Template $template): void
    {
        $headline2 = StringUtil::deserialize($template->headline2);

        $template->headline2 = \is_array($headline2) ? $headline2['value'] : $headline2;
        $template->hl2 = \is_array($headline2) ? $headline2['unit'] : 'h1';
    }
}
