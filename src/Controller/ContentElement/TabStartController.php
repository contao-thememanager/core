<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Controller\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\Date;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(TabStartController::TYPE, category:'tabs')]
class TabStartController extends AbstractContentElementController
{
    public const TYPE = 'tabStart';

    private static array $tabStartElements = [];

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        $container = System::getContainer();

        // Do not display template in backend
        if ($container->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $template = new BackendTemplate('be_wildcard');
            $template->title = $model->tabLabel;
        }
        else
        {
            $labels = [];

            $template->tabGroup = $group = !!$model->tabGroup ? $model->tabGroup : 'article_'.$model->pid;
            $template->tabLabel = $model->tabLabel;

            // Only build navigation for first item in every tab group
            if (null === self::getTabStartElements($model->pid, $group))
            {
                $cols = ["tl_content.pid=? AND tl_content.type=? AND tl_content.tabGroup=?"];

                if (!$container->get('contao.security.token_checker')->isPreviewMode())
                {
                    $time   = Date::floorToMinute();
                    $cols[] = "tl_content.invisible='' 
                           AND (tl_content.start='' OR tl_content.start<='$time') 
                           AND (tl_content.stop='' OR tl_content.stop>'$time')";
                }

                $tabElements = ContentModel::findBy(
                    $cols,
                    [$model->pid, 'tabStart', $model->tabGroup],
                    ['order' => 'sorting ASC']
                );

                if (
                    null !== $tabElements &&
                    !empty($labels[$model->pid][$group] = $tabElements->fetchEach('tabLabel'))
                )
                {
                    self::$tabStartElements = $labels;

                    $template->tabNav = true;
                    $template->tabNavElements = $labels[$model->pid][$group];
                }
            }
        }

        return $template->getResponse();
    }

    /**
     * Gets the previously set tabs
     */
    private function getTabStartElements($pid, $group): ?array
    {
        if (isset(self::$tabStartElements[$pid][$group]))
        {
            return self::$tabStartElements[$pid][$group];
        }

        return null;
    }
}
