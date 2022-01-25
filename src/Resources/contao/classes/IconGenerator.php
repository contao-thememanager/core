<?php
/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core;

use Oveleon\ContaoThemeCompilerBundle\FileCompiler;

class IconGenerator
{
    const MSG_ICON = 'tc_info icon';

    /**
     * Generate icon set when compiling the theme
     */
    public function generateIconSet(FileCompiler $objCompiler): void
    {
        $objCompiler->msg('Icons', FileCompiler::MSG_HEAD);

        // Fixme: Message example
        for($i=1; $i<=5; $i++)
        {
            $objCompiler->msg("Icon $i added", self::MSG_ICON);
        }

        // Fixme: Add file to compiler example
        //$objCompiler->add('pathToCompiledCSSFile);
    }
}
