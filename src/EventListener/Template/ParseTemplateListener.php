<?php

namespace ContaoThemeManager\Core\EventListener\Template;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\StringUtil;
use Contao\Template;

/**
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
