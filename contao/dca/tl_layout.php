<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

use Contao\StringUtil;
use Contao\DataContainer;
use Contao\Message;

$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['load_callback'][] = ['tl_layout_thememanager', 'checkSelectedFramework'];

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class tl_layout_thememanager extends Contao\Backend
{
    /**
     * Display message if layout.css or responsive.css is selected
     */
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
