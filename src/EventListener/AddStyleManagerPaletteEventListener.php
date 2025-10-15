<?php

declare(strict_types=1);

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
            $table === 'tl_module' && \in_array($palette, ['root_page_dependent_modules', 'unfiltered_html', 'html'], true)
            || $table === 'tl_content' && \in_array($palette, ['unfiltered_html', 'html', 'accordionStop', 'sliderStop', 'wrapperStopContent', 'wrapperStop'], true)
            || $table === 'tl_form_field' && \in_array($palette, ['html', 'fieldsetStop', 'hidden', 'hiddencustom'], true)
        ) {
            $event->skipPalette();
        }
    }
}
