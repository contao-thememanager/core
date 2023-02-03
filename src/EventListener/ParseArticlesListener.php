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

        $dateType   = $module->news_fullTime ? 'datim' : 'date';
        $dateFormat = $module->news_fullTime ? $module->news_datimFormat : $module->news_dateFormat;

        $template->date = self::getParsedTimeFormat($dateType, $newsEntry['tstamp'], $objPage, $dateFormat);

        // Add various date and time variables
        $template->time      = self::getParsedTimeFormat('time', $newsEntry['tstamp'], $objPage, $module->news_timeFormat);
        $template->year      = Date::parse('Y', $newsEntry['tstamp']);
        $template->yearShort = Date::parse('y', $newsEntry['tstamp']);

        if ($module->news_removeBy)
        {
            $template->author = ltrim($template->author, ($GLOBALS['TL_LANG']['MSC']['by'] . ' '));
        }

        // Modify comment variables
        if (1 === $template->numberOfComments)
        {
            $template->commentCount = $GLOBALS['TL_LANG']['MSC']['commentCountOne'];
        }
        else if (!!$template->numberOfComments)
        {
            $template->commentCount = sprintf($GLOBALS['TL_LANG']['MSC']['commentCountMultiple'], $template->numberOfComments);
        }

        $template->hasComments = !!$template->numberOfComments;
    }

    private function getParsedTimeFormat(string $type, int $tstamp, $objPage, ?string $format): string
    {
        if ($format && Date::isNumericFormat($format))
        {
            return Date::parse($format, $tstamp);
        }

        return Date::parse($objPage->{$type.'Format'}, $tstamp);
    }
}
