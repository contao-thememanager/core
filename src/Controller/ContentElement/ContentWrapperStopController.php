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
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(ContentWrapperStopController::TYPE, category:'wrapper')]
class ContentWrapperStopController extends AbstractContentElementController
{
    public const TYPE = 'wrapperStop';

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        // Do not display template in backend
        if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $template = new BackendTemplate('be_wildcard');
        }

        return $template->getResponse();
    }
}
