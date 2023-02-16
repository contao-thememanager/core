<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\EventSubscriber;

use Contao\ArrayUtil;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    protected $scopeMatcher;
    protected $security;

    public function __construct(ScopeMatcher $scopeMatcher, Security $security)
    {
        $this->scopeMatcher = $scopeMatcher;
        $this->security     = $security;
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
            $GLOBALS['TL_CSS'][] = 'bundles/contaothememanagercore/backend/css/ctmcore.css|static';

            if (file_exists('assets/ctmcore/css/_icon.css'))
            {
                $GLOBALS['TL_CSS'][] = 'assets/ctmcore/css/_icon.css|static';
            }

            /** @var User $user */
            $user = $this->security->getUser();

            if (null !==$user && $user->show_ctm_colors && file_exists('assets/ctmcore/css/_backendColors.css'))
            {
                $GLOBALS['TL_CSS'][]        = 'assets/ctmcore/css/_backendColors.css|static';
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaothememanagercore/backend/js/ctmcore.js|static';
            }
        }
    }
}
