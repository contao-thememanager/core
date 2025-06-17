<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace ContaoThemeManager\Core\Util;

/**
 * Provides array manipulation methods
 */
class ArrayUtil
{
	/**
	 * Add, remove or replace values from the current array based on your configuration.
	 */
	public static function alterListByConfig(array $list, array $config): array
	{
        $isList = array_is_list($list);

		$newList = array_filter($config, static fn ($newValue) => !\in_array($newValue[0], array('-', '+'), true), $isList ? 0 : ARRAY_FILTER_USE_KEY);

		if ($newList)
		{
			$list = $newList;
		}

		foreach ($config as $k => $v)
		{
            $item = $isList ? $v : $k;
			$prefix = $item[0];
			$value = substr($item, 1);

			if ('-' === $prefix)
			{
                if (!$isList) {
                    unset($list[$value]);
                } elseif (\in_array($value, $list, true)) {
                    unset($list[array_search($value, $list, true)]);
                }
			}
			elseif ('+' === $prefix)
			{
                if (!$isList) {
                    $list[$value] = $v;
                } elseif (!\in_array($value, $list, true)) {
                    $list[] = $value;
                }
			}
		}

		$isList ? sort($list) : ksort($list);

		return $list;
	}
}
