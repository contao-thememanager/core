# Changelog

**Actively maintained**

+ [Version 2.0.*](#version-20)

**No longer supported**

+ [Version 1.5.*](#version-15)
+ [Version 1.4.*](#version-14)
+ [Version 1.3.*](#version-13)
+ [Version 1.2.*](#version-12)
+ [Version 1.1.*](#version-11)

------

## Version 2.0.*

- Major Release - ThemeManager Version 2
- An upgrade from the previous 1.x is **NOT possible and NOT recommended**.

### Upgrade and migration from 1.x

Due to major breaking changes in the ThemeManager, such as new and reordered StyleManager options, major template
changes, new class names, deprecations, and removals, we decided to release the ThemeManager as version 2.
Upgrading is nearly impossible and not recommended. In case you accidentally upgraded from version 1.x, please follow
this documentation on how to [restore from a backup](https://docs.contao.org/manual/en/cli/db-backups/).

### What's New in 2.x

#### Global / Quality of Life Changes

- Simultaneous support for Contao 4.13.x, 5.1.x, 5.2.x
- Complete rewrite of the framework to use [CSS custom properties](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)
- Configure images within the filesystem as backgrounds and automatically generate the CSS after compiling
- Prefilled layout settings when creating a new layout
- "main-below" is now the default when creating new articles
- CSS inheritance to pass down options into child templates:
  > List items can now use image text settings, get paddings, borders, backgrounds, and more by inheritance using CSS
  custom properties.
- New list settings for vertical & horizontal flexbox properties
- Added default image sizes
  > Make sure to have an image library installed and/or change it to another
  image_service [[1]](https://github.com/contao/contao/issues/641#issuecomment-522635797) [[2]](https://github.com/contao/docs/issues/464)
- Reworked wrappers to allow infinite nesting
- Added color preview for text and background colors (user settings)
- Background-images and colors can now be set within content-elements, modules, layouts, and pages
- Box (previously known as boxed) can be set on every eligible component or their children
- Box-elements are no longer text-color-regular by default and can be changed within the StyleManager options
- Generated icons are now available within lists, downloads, and forms
- All forms are now grid by default (login, password, comments, etc.)
- You no longer need to select the image URL for image-list and image-text-lists to add the lightbox URL
- Dynamic Style-Manager options after compilation (style-manager-tm-config)
- Buttons now react to text-color-invert
- New asset compiler overview
- Reworked default theme configuration
- Allow saving & compiling on newly created themes

_____

#### ThemeManager Configuration

- New ThemeManager configuration for better overview
- Added text-transform for headlines
- Added colors, font-weights, letter-spacings, and text-transform for content-headlines
- Added image-text-behavior and image-text-ratio-options (dynamic property):
- Added aspect-ratios for images (dynamic property)
- Added min-height for articles and article vertical height (dynamic property)
  > Dynamic properties are available within the StyleManager options after compilation
- Added article outer padding for more spacing on mobile devices
- Added the possibility to change the select dropdown and checkbox symbol within ThemeManager config
- Added table styles
- Added header options
- Added breadcrumb separator styles
- Changed ThemeManager configuration for better UX
  ```
  ## Old order ##
  - Base-Settings
  - Colors
  - Typography
  - Headings
  - Headings (Content)
  - Heading (Spacings)
  - Links
  - Layout
  - Article
  - Grid
  - Form: General
  - Form: Label / Legend
  - Form: Select
  - Form: Checkbox / Radio
  - Form: Miscellaneous
  - Form-field: Image / Icon
  - Icon
  - Buttons
  - Button-Design (Primary)
  - Button-Design (Secondary)
  - Button-Design (Alternative)
  - Spacings (Inner)
  - Spacings (Outer)
  - Divider
  - Boxed
  - Table
  - Image-Text
  - Header
  - Navigation
  - Misc

  ## New order ##
  - Global
    - Base-Settings
    - Colors
  - Fonts
    - Typography
    - Links
  - Headings
    - Headings
    - Headings (Content)
    - Heading (Spacings)
  - Buttons
    - Buttons: General
    - Button-Design (Primary)
    - Button-Design (Secondary)
    - Button-Design (Alternative)
  - Components
    - Boxed
    - Divider
    - Table
    - Icon
  - Form
    - Form: General
    - Form: Label / Legend
    - Form: Select
    - Form: Checkbox / Radio
    - Form: Miscellaneous
    - Form-field: Image / Icon
  - Modules
    - Navigation
    - Breadcrumb
  - Header
  - Misc
  - Layout
    - Breakpoints
    - Behavior
    - Article
    - Grid
    - Spacings (Inner)
    - Spacings (Outer)
    - Image-Text

  ```
  ![image](https://github.com/contao-thememanager/core/assets/55794780/fb52c242-424f-48b3-b922-2ad9d4623e73)
_____

#### StyleManager Options

- Dark mode support for StyleManager
- New StyleManager groups
    - Global (outer scope)
    - Component (component scope)
    - Element (children scope)
- New header options (fixed, undocked)
- New article spacings (self-overlap, next-overlap)
- Added article heights (vertical height)
- New grid alignments (horizontal-items and vertical-items)
- Added headline colors
- Added pagination options
- Added background options for global, component, and element scope
- New background options (image, attachment, repeat, size, horizontal position, vertical position)
- New image-text options for image-text, image-text-list, newslist, eventlist (cards)
- Added responsive spacings (margins for every direction) for components and elements
- Added responsive paddings for components and elements
- Added box-mode for list-items
- Added vertical alignment for list-items
- Added options for lists and downloads (grid-list, direction, gutter, style, flex, markercolor)
- Added vertical alignment for texts
- Changed translation for spacing to margin

_____

#### Framework

- New structure and CSS (base, components, layout, modules, overrides, styles, utils)
- Changed classnames for better readability
- Now based on custom properties
- Added download styles
- Added image-text styles
- Added pagination styles
- Added breadcrumb styles
- Added table styles
- Added background options
- Added image options
- Added header options
- Added list options
- Added responsive margins and paddings
- And more...

_____

#### Content Elements and Modules

- Added icon content-element
- Added support for almost every Contao core component
- Added support for [faq](https://github.com/contao/faq-bundle)
- Added support for [news](https://github.com/contao/news-bundle)
- Added support for [comments](https://github.com/contao/comments-bundle)
- Added support for [listing](https://github.com/contao/listing-bundle)
- Added support for [events](https://github.com/contao/calendar-bundle)

_____

#### Modifications

##### Hyperlinks

- added .c_link to every link component within modules and elements

##### Gallery

- Integrated ThemeManager framework into the gallery component

##### News

- Added the possibility to hide the author name within the newslist
- Added the possibility to remove the "by" prefix from news
- Added the possibility to set a custom time format for news

##### FAQ

- Added the possibility to change headline types (h1-h6) of FAQ page through style manager options

##### Comments

- Added new translations ('1' comment and 'n' comments)

------

## Version 1.5.*

- Dropped Contao 4.9 Support (Use Contao 4.13 instead)

### Changes

- changed license to AGPLv3
- importing the style-manager-core.xml is not necessary anymore (they are loaded from the bundle itself)
- dropped Contao 4.9 support
- updated all templates to contao 4.13
- slider-config is no longer a template variable
- form-input icons are now generated through an icon-font
- replaced webmozart/path-util with symfony/filesystem
- IconFonts can now be set within Contao Settings: **ThemeManager settings** (You no longer need to use
  $GLOBALS['CTM_SETTINGS']['iconFont'] in config.php)
- changed deprecated scan() method in IconGenerator to scandir()
- updated dependencies:
    - contao-theme-compiler-bundle to ^1.2
    - contao-config-driver-bundle to ^1.1

### Additions

- added an UPGRADE and CHANGELOG.md for updating from version 1.4 to 1.5 (when template and style-manager changes have
  been made)
- added icon css generation from a iconset.xml
- added a possibility to set icons into buttons
- added a possibility to set icons into list items
- added methods to generate stylemanager xml files
- added list layouting and styles (vertical and horizontal)
- added table styles
- added a hyperlink list
- added event-list support
- added figcaption support to every image content-element
- added a possibility to set background and text-color within wrapper-elements
- added webp as valid image type for thememanager image-elements
- added visibility options to thememanager list-elements

### Changes to the css-framework

The minified framework size has been reduced from 84kB (11kB gzip) to 54kB (8.7kB gzip).
Most of the framework has been changed to use custom properties. For more information on them,
check https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties

- reworked box-shadow styles
- headlines now have a standard margin of 1rem
- removed overflow hidden from every block element
- buttons are now `display: inline-flex`
- text within full-width buttons is now centered
- minor tweaks to various css to save framework size
- changed specifity of layout-absolute within image-text components
- changed grid-framework percentages to 4 decimal places (e. g. 33.33333333% to 33.3333%)

- reworked spacings to include custom properties
- reworked layout to include custom properties
- reworked form styles to include custom properties
- reworked typography to include custom properties
- reworked buttons to include custom properties
- reworked text- and link-colors to include custom properties
- combined component-spacing, text-alignment and hl-alignment

- added new utility functions:
    - selector-split($string, $separator): converts a sass string into a sass list
    - list-to-string($list, $separator, $nested): converts a sass list into a string

- added new breakpoint mixins:
    - loop-css-map($map): iterates through a sass map and outputs a key value pair with selector name
    - generate-infix-breakpoint-styles($list): generates css from a sass map with css for all set-up breakpoints (See
      styles/_alignmentSpacing.scss on how to use it)

### Removal

- removed form-design for form-submit buttons / use normal button-design instead
- removed IE and Firefox <18 support
- removed deprecated util-functions `button-padding()` and `shorten-decimal-value()` - use `subList()`
  and `shortPercentage()` instead
- removed `font-smoothing: antialiased`from the icon-mixin
- removed deprecated {{ua::class}} from fe_page, use this insert-tag within the body-class in layout instead

------

## Version 1.4.*

- Dropped PHP < 7 Support

### Additions

- Added more variables for checkbox- and radio-elements (Their style isn't tied to form settings anymore)
    - $form-checkbox-radio-label-color-invert `(Inverted color for labels when using text-color: invert)`
    - $form-checkbox-radio-border-width `(Border-width)`
    - $form-checkbox-radio-border-style `(Border-style)`
    - $form-checkbox-radio-background `(Background-color)`
    - $form-checkbox-radio-background-hover `(Background-color when hovered)`
    - $form-checkbox-radio-background-focus `(Background-color when checked)`
    - $form-checkbox-radio-symbol-font-size `(Font-size for '✔'-symbol)`
    - $form-checkbox-radio-symbol-color `(Color for '✔'-symbol)`


- Added new utility functions and added documentation for all utility functions
    - isValidUnit($type) `(Checks if unit is px or rem)`
    - px() `(Converts rem to px)`
    - sumList() `(Return the sum of two shorthand css)`
    - subList() `(Return the difference of two shorthand css)`
    - shortPercentage() `(Returns percentage() with 4 decimal places)`
    - minUnit() `(Compare two values and return the minimum - Can be used with px and rem)`
    - maxUnit() `(Compare two values and return the maximum - Can be used with px and rem)`
      > To use the new variables, make sure to fill the new variables in "Form: Checkbox / Radio" if you are installing
      it on a running version
    - Added a root.css with following custom properties that can be added to your layout settings after compiling
      (Usage: https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)
        ```sass
            --body-bg
            --body-bg-rgb
            --color-primary
            --color-primary-rgb
            --color-secondary
            --color-secondary-rgb
            --color-light
            --color-light-rgb
            --color-dark
            --color-dark-rgb
            --color-text-regular
            --color-text-invert
            --font-family-base
            --font-size-base
            --line-height-base
            --line-height-headline
            --font-weight-base
            --font-weight-light
            --font-weight-regular
            --font-weight-medium
            --font-weight-semibold
            --font-weight-bold
            --box-shadow
        ```
- Added a Save and compile button to the ThemeManager configuration
- PHP 8 Compatibility
- Contao 4.12 Compatibility

### Changes

- Changed ```$font-size-base``` to ``number``-type
- ``rem()``, ``sum()`` and ``sub()`` should now output units (instead of strings) and can be used inside other functions
- ``standardize()`` should now return a list-type instead of a string ``(output the full shorthand with 4 values)``.
- ``rem()`` can now be used with pixel values
- ``button-padding()`` is now ``subList()`` and shouldn't be used anymore
- ``shorten-decimal-value()`` was rewritten to ``shortPercentage()``
- Changed all paddings for buttons to subList
- Checkbox labels should now behave properly and react to checkbox-sizes
- The font-size for the checkbox-'✔'-symbol is now limited to the inner width of a checkbox
- The form palette of the ThemeManager configuration was split into multiple sections to make it more understandable
    ```
    - Form: Textarea (Line-height for widget-textarea)
    - Form: Label / Legend (Styles for label and legend)
    - Form: Select / Input / Textarea (Styles for input-, select- and textarea-elements)
    - Form: Checkbox / Radio (Styles for checkbox- and radio-elements)
    - Form: Miscellaneous (Styles such as select-symbols or form-validation)
    ```
- Added PHP8 support
- Changed select-icon for widget-select (▼) for cross-browser support (Safari)
- Removed html5shiv-printshiv.js (Deprecated)
-

### Deprecated

- button-padding() will be removed in the next ThemeManager version
- shorten-decimal-value() will be removed in the next ThemeManager version

### Bugfixes

- Fixed a bug where 'ol'-elements (ordered lists)  were not considered for text-color-invert
- Fixed a bug where 'overflow: visible' (.ov) was overwritten by other styles
- Fixed a translation error for the german column-width descriptions
- Added an invert-color for checkbox labels
- Fixed a bug that would occur when saving the Theme-Manager configuration with Contao 4.9.18 or higher.
- Fixed Widget-captcha not reacting to text-color-invert

------

## Version 1.3.*

### Additions

- Added text-alignment for headlines with responsive behaviour
  ``hl-align-left``
  ``hl-align-right``
  ``hl-align-center``
  ``hl-align-justify``
- Added *$link-font-weight* to thememanager configuration
- Added support for form fieldsets to inherit our grid framework
- Added responsive styles from contao
- Added *$form-textarea-lineheight* to thememanager configuration
- Added *wrapper-start* and *wrapper-stop* - content elements (and their boxed variants)
- Added **boxed-shadow** for grid-lists (You are now able to use box shadows for boxed-elements without them being cut
  off - please keep in mind that the $box-shadow variable should not be bigger than grid-gutter)
- Added 12 new layout options for Image-Text elements for absolute positioning of text above images
  ``Absolute Top, Absolute Center, Absolute Bottom`` (For 100% width of c_text and positioning on vertical axis)
  ``Absolute Left Top, Absolute Left Center, Absolute Left Bottom``
  ``Absolute Middle Top, Absolute Middle Center, Absolute Middle Bottom``
  ``Absolute Right Top, Absolute Right Center, Absolute Right Bottom``
- Added text-alignment for hyperlinks and toplinks
- Added a charset.css (charset utf8) to be at first position of combined css files
- Added $form-select-option-color and $form-select-option-background (You are now able to configure these as well and
  change the colors for dropdown select options)
- Added a utility function (shorten-decimal-value) for usage in percentage calculations to shorten percentage values to
  4 decimal places
- Added form-validation dropdown in Theme-Manager configuration
    - The form-validation highlights input, select and textarea fields that are empty and contain non-valid input.
        - Following options have been added:
            - None
            - Focus (Error styles only on focus)
            - Always (Always show error styles for non-valid form-fields)
- Added an option to give buttons a full-width (setting them display-block)
  > With display block, buttons will behave like text in terms of layouting. You can use the usual text-alignment to
  align it on the horizontal axis
- Download links in ce_download and ce_downloads can be shown as buttons now
- Added grid-list behaviour for ce_downloads
  > The grid-list behaviour will activate if any settings for the grid-list are active. This will parse the other part
  of the template and make sure your previously styled lists won't break with this update. If you display the downloads
  as buttons, it is recommended to use the new full-width option
- Added 'boxed' option for following modules#
    - login
    - personal-data
    - registration
    - change password
    - lost password

### Changes

- Restricted units for all fields in theme configuration to only allow px and rem
  > We are aware of the restriction but most units were redundant and would not work. The only exception made was adding
  em for heading letterspacings. A few components in our framework work with calculations. „Wrong usage“ of units for
  some variables would have led to unexpected behaviour
- Removal of unnecessary styles for justify content
- Subheadings can now contain more than 255 characters (You are now able to use „Headline 2“ to add additional text)
- Added p and div for headings (You are now able to fake a heading by using „p“ or „div“ with a headline class – Search
  Engine Optimization)
- Removed standard-appearance of buttons
- Added overflow visible attribute to inside containers of .ov elements
- Changed how hl-design-lined works (It is now able to inherit text-alignment and the position is calculated based on
  thememanager configuration)
- Converting RSCE to HTML has been disabled
  > Components in our framework are rock solid custom elements. Converting these to HTML will break them.
- Added a message for redundant css framework in layout settings
  > Adding a css framework with drastic layout changes like holy-grail on top of the theme-manager may lead to
  unexpected behaviour
- Changed .error styles to :not(:valid) (The error markdown is removed if the new input is valid)
- Extending .error styles via extend for more general usage
- Removed following parts from the user-select-mixin due to not being needed
  anymore (https://caniuse.com/?search=user-select)

  ``-webkit-touch-callout``

  ``-khtml-user-select``

  ``-moz-user-select``

  ``-ms-user-select``

### Deprecated / Moved

- Removed headline-design.scss from core and moved it into additonal styles
  > This feature was removed due to it’s css size. The removal of it reduces the framework by 5kB. You are still able to
  use it by following these steps:
    1. Download the scss from additional-styles
    2. Add it to your theme compilation settings (Skin source files)
    3. Compile your theme (Skin source files can use the configurated theme manager variables which are needed in
       headline-design.scss for calculating the right spacings)
    4. Add the new generated css file to your layout
    5. Import the style-manager-headline-design.xml to your Stylemanager (StyleManager > Import)

### Bugfixes

- Fixed a bug with no-gutter not working correctly
- Fixed a bug where headline-fields would not be added to elements
- Added missing text-alignment (justify)
- Added subheadings to navigation modules
- Changed layout-top calculation to work with px, rem and all shorthand values for boxed elements
- Removed placeholder classes for form-field-image
- Fixed a bug with layout-absolute top images not being resized to 100%
- Fixed a bug with boxed layout-top not working with multiple values
- Fixed a bug with small-gutter not being set to form_fieldsetStart
- Removed the option to give wrappers and boxed-wrappers "layout>boxed" as it didn't work due to their structure
- Added palettes for wrapper and boxed-wrapper content-element
  > You should now be able to use the stylemanager with these content-elements
- Fixed a bug with floats not being cleared for icon- and image-text elements
- Fixed a bug with wrapper-components not reacting to vertical alignments
- Added ajaxform_inline template with a '.inside' class (forms in other plugins not working correctly)
- Fixed a bug where usage of form_checkbox and form_radio in other modules may throw an error

------

## Version 1.2.*

### Additions

- Icon font can now be changed via the config
- Added no-gutter behaviour for y-spacings for all breakpoints
- Added *max-breakpoint* - mixin for max-width media-queries:
  ```@include max-breakpoint('s') {...} would create: @media (max-width: 767px) {...}```
- Added news integration for Contao
- Added $form-checkbox-radio-size to checkbox and radio size
- Added *form-input-symbol-font-size* to change the select symbol within select-fields
- The select icon now reacts to form-input-height and form-grid to always be centered
- Added *form-input-font-size-error* (font-size of error messages)
- Added *form-checkbox-radio-border-colo*r (border-color for checkbox and radio elements) and their hover and focus
  state
- Added *form-input-mandatory-symbol-color-error* (The '*' - symbol will now turn to your designated color when an error
  appears)
- Added *form-field-image-width* and *-height* (You are now able to add images or icons to your form-fields)
- Added *form-field-icon-color / -background / -size* (You are now able to add explanation icons to text-fields in
  form-fields, either left or right)
- Added *form-field-image* - mixin for simplified creation of the needed css-classes to be able to use images above form
  fields:

```@include form-field-image($class-suffix, $content, $blnIcon, $font-size)
with
@include form-field-image(1, '/files/foo/bar.jpg')
will output:
.form-image.form-img-#{$class-suffix} {... &:before {
      background-image: url('/files/foo/bar.jpg'
```

> You can select your images within form fields after adding them to your style-manager configuration within
> StyleManager -> Form-Field-Image -> Image -> CSS-Classes  ( Added 10 placeholder classes for use)

- Added *form-field-icon* - mixin for simplified creation of the needed css-classes to be able to use explanation icons
  within form-fields:

```@include form-field-icon($class-suffix, $content, $blnIcon, $font-size)
with
@include form-field-icon(test, '@')
will output:
.f-icon-test > .input-container:before {
   content: '@';
}
```

```@include form-field-icon(test, '\e800', true, rem(20)) will parse the additional parameters into the icon mixin for using your icon-set```
> You can select your explanation icon within form fields after adding them to your style-manager configuration within
> StyleManager -> Form-Field-Icon -> Icon -> CSS-Classes (Added m² / @ / € / $ for explanation)

- Updated style-manager-core.xml with new additions:

> Form-Input-Icon: Alignment (by default centered, left, right) and Icons (m², @, € and $) || New icons can be added
> using the *form-field-icon*-mixin
> Form-Field-Image: Alignment (by default centered, left, right) and Images (10 placeholder images for following
> classes: form-img-1 to form-img-10 || New images can be added using the *form-field-image*-mixin

- Added explanations and their translations for every config variable in the theme-manager configuration

### Changes

- Changed form-templates to include input-containers
- Changed mandatory symbols to be within input-containers
- Updated all config variables placeholders to match the framework config
- All config variables will now be prefilled when opening the theme-manager config for the first time
- Removed conflict with scssphp/scssphp version 1.2
- Input fields in theme-manager-config were raised to 32 characters
- Changed mod_newslist template to integrate stylemanager configuration
- Updated style-manager-core.xml

### Bugfixes

- Fixed a bug with border-radius behaviour in layout top image-text-lists
- Fixed a bug where c_icon would not react to alignment
- Fixed grid in grid layout not correctly displayed
- Fixed a bug where form-buttons would not get the selected style-manager styles
- Fixed a few translation errors
- The captcha field was not completely hidden
- no-gutter not working correctly
- Labels in tl_content and tl_module will be translated again
- Callback methods for rsce not needed anymore
- Don't add headline fields if they already exist
- Missing headlines will be displayed again

------

## Version 1.1.*

### Changes

- Contao 4.9 only
- Changed 'l' breakpoint to 1264px to correctly display medium inside container with 1200px width
- Added missing variables for forms

### Additions

- Added new button features