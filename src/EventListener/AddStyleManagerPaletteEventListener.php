<?php

namespace ContaoThemeManager\Core\EventListener;

use Oveleon\ContaoComponentStyleManager\Event\AddStyleManagerPaletteEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\Event;

#[AsEventListener(AddStyleManagerPaletteEvent::class)]
class AddStyleManagerPaletteEventListener extends Event
{
    public function __invoke(AddStyleManagerPaletteEvent $event): void
    {
        $table = $event->dc->table;
        $palette = $event->palette;

        if (
            'tl_module' === $table && in_array($palette, ['root_page_dependent_modules', 'unfiltered_html', 'html'])
            || 'tl_content' === $table && in_array($palette, ['unfiltered_html', 'html', 'accordionStop', 'sliderStop', 'wrapperStopContent', 'wrapperStop'])
            || 'tl_form_field' === $table && in_array($palette, ['html', 'fieldsetStop', 'hidden', 'hiddencustom'])
        )
        {
            $event->skipPalette();
        }
    }
}
