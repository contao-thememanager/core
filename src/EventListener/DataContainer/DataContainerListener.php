<?php

namespace ContaoThemeManager\Core\EventListener\DataContainer;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\DataContainer;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Security;
use Contao\Message;

class DataContainerListener
{
    public function __construct(
        protected ContaoFramework $framework,
        protected Connection $connection,
        protected Security $security
    ){}

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
}
