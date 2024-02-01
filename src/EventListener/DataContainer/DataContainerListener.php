<?php

namespace ContaoThemeManager\Core\EventListener\DataContainer;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Input;
use Contao\Message;
use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Security;

class DataContainerListener
{
    public function __construct(
        protected ContaoFramework $framework,
        protected Connection $connection,
        protected Security $security
    ){}

    #[AsCallback(table: 'tl_layout', target: 'fields.headerHeight.load')]
    #[AsCallback(table: 'tl_layout', target: 'fields.footerHeight.load')]
    #[AsCallback(table: 'tl_layout', target: 'fields.widthLeft.load')]
    #[AsCallback(table: 'tl_layout', target: 'fields.widthRight.load')]
    public function checkLayoutMisconfiguration($value, DataContainer $dc): array|string
    {
        if (empty($value))
        {
            return '';
        }

        $array = StringUtil::deserialize($value);

        if (
            empty($array) ||
            !is_array($array) ||
            (empty($array['unit'] ?? '') && empty($array['value'] ?? ''))
        ) {
            return $value;
        }

        Message::addError(
            vsprintf(($GLOBALS['TL_LANG']['tl_layout']['misconfiguration'] ?? null), [
                ($GLOBALS['TL_LANG']['tl_layout']['headerHeight'][0] ?? null),
                ($GLOBALS['TL_LANG']['tl_layout']['footerHeight'][0] ?? null),
                ($GLOBALS['TL_LANG']['tl_layout']['widthLeft'][0] ?? null),
                ($GLOBALS['TL_LANG']['tl_layout']['widthRight'][0] ?? null)
            ])
        );

        return $array;
    }

    #[AsCallback(table: 'tl_layout', target: 'fields.framework.load')]
    public function checkSelectedFramework($value, DataContainer $dc): array|string
    {
        if (empty($value))
        {
            return '';
        }

        $array = StringUtil::deserialize($value);

        if (empty($array) || !is_array($array))
        {
            return $value;
        }

        if (in_array('responsive.css', $array) || in_array('layout.css', $array))
        {
            Message::addInfo(
                sprintf(($GLOBALS['TL_LANG']['tl_layout']['frameworkMessage'] ?? null), ($GLOBALS['TL_LANG']['tl_layout']['responsive.css'][0] ?? null) . ' (responsive.css), ' . ($GLOBALS['TL_LANG']['tl_layout']['layout.css'][0] ?? null) . ' (layout.css)')
            );
        }

        return $array;
    }

    public function removeLibraryHint(DataContainer $dc): void
    {
        if ($_POST || Input::get('act') != 'edit')
        {
            return;
        }

        $security = System::getContainer()->get('security.helper');

        if (!$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_MODULE, 'themes') || !$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_LAYOUTS))
        {
            return;
        }

        $objCte = ContentModel::findByPk($dc->id);

        if ($objCte === null)
        {
            return;
        }

        if ($objCte->type == 'gallery')
        {
            Message::reset();
        }
    }
}
