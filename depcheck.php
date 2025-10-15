<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    ->ignoreUnknownClasses([
        DomNode::class,
        \Contao\CalendarBundle\ContaoCalendarBundle::class,
        \Contao\FaqBundle\ContaoFaqBundle::class,
        \Contao\NewsBundle\ContaoNewsBundle::class
    ])

    ->ignoreErrorsOnPackage('contao/manager-plugin', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackage('madeyourday/contao-rocksolid-custom-elements', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('madeyourday/contao-rocksolid-icon-picker', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('oveleon/contao-config-driver-bundle', [ErrorType::UNUSED_DEPENDENCY])
;
