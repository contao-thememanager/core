<?php

namespace ContaoThemeManager\Core\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Date;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\PageModel;

#[AsHook('parseArticles')]
class ParseArticlesListener
{
    public function __invoke(FrontendTemplate $template, array $newsEntry, Module $module): void
    {
        /** @var PageModel $objPage */
        global $objPage;

        // Add various date and time variables
        $template->date = Date::parse($objPage->dateFormat, $newsEntry['tstamp']);
        $template->time = Date::parse($objPage->timeFormat, $newsEntry['tstamp']);
        $template->dayMonth = Date::parse('d. F', $newsEntry['tstamp']);
        $template->dayMonthShort = Date::parse('d. M.', $newsEntry['tstamp']);
        $template->year = Date::parse('Y', $newsEntry['tstamp']);

        // Add author name without additions
        $template->authorName = str_replace($GLOBALS['TL_LANG']['MSC']['by'] . ' ', '', $template->author);

        // Modify comment variables
        if ($template->numberOfComments > 0)
        {
            if ($template->numberOfComments === 1)
            {
                $template->commentCount = $GLOBALS['TL_LANG']['MSC']['commentCountOne'];
            }
            else
            {
                $template->commentCount = sprintf($GLOBALS['TL_LANG']['MSC']['commentCountMultiple'], $template->numberOfComments);
            }
        }

        $template->hasComments = !!$template->numberOfComments;
    }
}