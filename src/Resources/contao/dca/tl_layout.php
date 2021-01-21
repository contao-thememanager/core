<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
*/

$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['load_callback'][] = array('tl_layout_thememanager', 'checkSelectedFramework');

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class tl_layout_thememanager extends Contao\Backend
{
    /**
     * Display message if layout.css or responsive.css is selected
     *
     * @param $value
     * @param \Contao\DataContainer $dc
     *
     * @return string
     */
	public function checkSelectedFramework($value, Contao\DataContainer $dc)
	{
        if (empty($value))
		{
			return '';
		}

		$array = Contao\StringUtil::deserialize($value);

		if (empty($array) || !is_array($array))
		{
			return $value;
		}

		if (in_array('responsive.css', $array) || in_array('layout.css', $array))
		{
            Contao\Message::addInfo(
                sprintf($GLOBALS['TL_LANG']['tl_layout']['frameworkMessage'], $GLOBALS['TL_LANG']['tl_layout']['responsive.css'][0] . ' (responsive.css), ' . $GLOBALS['TL_LANG']['tl_layout']['layout.css'][0] . ' (layout.css)')
            );
		}

		return $array;
	}
}
