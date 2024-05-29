# Upgrading contao-thememanager

+ [Version 2.0.* to ^2.1.*](#version-20-to-21)
+ [Version 1.* to 2.*](#version-1-to-2)

_____


## Version 2.0.* to ^2.1.*

### Twig

> [!IMPORTANT]  
> If you have overwritten the HTML-Templates for following contao components, you'll have to rewrite them into twig.
> Getting used to twig right now is the best long-term goal to provide proper updates from contao elements without
> relying on modifying too many tempplates.
>
> ```php
> \Contao\ContentHeadline::class;
> \Contao\ContentList::class;
> \Contao\ContentText::class;
> \Contao\ContentTable::class;
> \Contao\ContentHyperlink::class;
> \Contao\ContentToplink::class;
> \Contao\ContentImage::class;
> \Contao\ContentGallery::class;
> \Contao\ContentPlayer::class;
> \Contao\ContentYouTube::class;
> \Contao\ContentVimeo::class;
> \Contao\ContentDownloads::class;
> \Contao\ContentDownload::class;
> \Contao\ContentTeaser::class;

### I want to go back to HTML5

Please refer to the upgrade.md from Contao to if you want to restore the classes or install plugins like:

https://github.com/zoglo/contao-legacy-templates

### Classnames

The legacy `.ce_*`-classnames are reintroduced into the Twig-Templates. Please refer to the new ones in your own skin as
they will be removed in version 2.2.

| old class    | new class         |
|--------------|-------------------|
| ce_headline  | content-headline  |
| ce_list      | content-list      |
| ce_text      | content-text      |
| ce_table     | content-table     |
| ce_hyperlink | content-hyperlink |
| ce_toplink   | content-toplink   |
| ce_image     | content-image     |
| ce_gallery   | content-gallery   |
| ce_player    | content-player    |
| ce_youtube   | content-youtube   |
| ce_vimeo     | content-vimeo     |
| ce_downloads | content-downloads |
| ce_download  | content-download  |
| ce_teaser    | content-teaser    |

### Structure changes

The gallery element does not use `div` anymore and follows the original `ul` style - Grid lists work like before.

_____


## Version 1.* to 2.*

> Upgrading from version 1.* to 2.* is 
> 
> <strong>not possible and not recommended</strong> 
> 
> due to many breaking changes and a completely new 
structure of the framework and style-manager settings.
>
>If you have upgraded from version 1.* to 2.* and performed the 
migrations, you should use a previous database backup after installing version 1.* again.
