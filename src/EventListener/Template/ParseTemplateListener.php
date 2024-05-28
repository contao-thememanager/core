<?php

namespace ContaoThemeManager\Core\EventListener\Template;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\StringUtil;
use Contao\Template;

/**
 * @deprecated as of ThemeManager 2.1, to be removed in ThemeManager 2.2
 * @Hook("parseTemplate")
 */
class ParseTemplateListener
{
    public function __invoke(Template $template): void
    {
        $headline2 = StringUtil::deserialize($template->headline2);

        $template->headline2 = is_array($headline2) ? $headline2['value'] : $headline2;
        $template->hl2 = is_array($headline2) ? $headline2['unit'] : 'h1';
    }
}
