<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\EventSubscriber;

use Contao\ArrayUtil;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    protected $scopeMatcher;

    public function __construct(ScopeMatcher $scopeMatcher)
    {
        $this->scopeMatcher = $scopeMatcher;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $e): void
    {
        $request = $e->getRequest();

        if ($this->scopeMatcher->isContaoRequest($request))
        {
            ArrayUtil::arrayInsert($GLOBALS['TL_CSS'][], 0, 'bundles/contaothememanagercore/css/charset.css|static');
        }

        if ($this->scopeMatcher->isBackendRequest($request))
        {
            $GLOBALS['TL_CSS'][] = 'bundles/contaothememanagercore/css/ctmcore.css|static';

            if (file_exists('assets/ctmcore/css/_icon.css'))
            {
                $GLOBALS['TL_CSS'][] = 'assets/ctmcore/css/_icon.css|static';
            }
        }
    }
}
