# Upgrading contao-thememanager

+ [Version 1.4 to 1.5](#version-14-to-15)

## Version 1.4 to 1.5

+ [Utility functions](#v15-changes-to-utility-functions)
+ [Template changes](#v15-changes-to-templates)
+ [StyleManager options](#v15-stylemanager-options)
+ [StyleManager archives](#v15-stylemanager-archives)

### v1.5 Changes to utility functions
- removed button-padding(), use subList() instead
- removed shorten-decimal-value(), use shortPercentage() instead
---
### v1.5 Changes to templates
Some templates have been updated in this version to fully support Contao 4.13.
If you have overwritten any of the following templates, you need to adjust them:
___
#### Contao Templates
```
- block_searchable.html5
- block_unsearchable.html5
- ce_download.html5
- ce_downloads.html5
- ce_headline.html5
- ce_list.html5
- ce_table.html5
- ce_toplink.html5
- form_captcha.html5
- form_checkbox.html5
- form_fieldsetStart.html5
- form_fieldsetStop.html5
- form_password.html5
- form_radio.html5
- form_rage.html5
- form_row.html5
- form_select.html5
- form_submit.html5
- form_textarea.html5
- form_textfield.html5
- form_upload.html5
- form_wrapper.html5
- fe_page.html5
- member_default.html5
- member_grouped.html5
- mod_article.html5
- mod_changePassword.html5
- mod_eventlist.html5
- mod_login.html5
- mod_navigation.html5
- mod_newslist.html5
- mod_password.html5
- mod_search.html5
```
___
#### ThemeManager Templates
```
- ce_wrapperStart.html5
- ce_wrapperStart_gridless.html5
- ce_wrapperStartBoxed.html5
- ce_wrapperStop.html5
- ce_wrapperStopBoxed.html5
- rsce_icon_text.html5
- rsce_icon_text_config.php
- rsce_icon_text_list.html5
- rsce_icon_text_list_config.php
- rsce_image_list.html5
- rsce_image_list_config.php
- rsce_image_text.html5
- rsce_image_text_config.php
- rsce_image_text_list.html5
- rsce_image_text_list_config.php
- rsce_text.html5
- rsce_text_config.php
- rsce_text_list.html5
- rsce_text_list_config.php
```
---
### v1.5 StyleManager options
- removed form-design for form-submit buttons, use normal button-design instead
---
### v1.5 StyleManager archives
This version loads all style-manager options through a bundle configuration, 
hence you do not need to import the style-manager-*.xml files anymore.

If you've never customized any of the following ThemeManager Archives (all), you are able to 

**DELETE** 

the following archives:
___
#### Archives from contao-thememanager/core
```
- layout
- headline
- text
- button
- icon
- image
- divider
- background
- formInputIcon
- formimage
- articleSpacing
- gridForm
- gridColumn
- gridOffset
- gridOrder
- gridAlignment
- gridGutter
- gridList
- leftSpacing
- rightSpacing
- spacing
- navigation
```
___
#### Archives from contao-thememanager/ctm-tiny-slider
```
- sliderConfig
- slider
- sliderXS
- sliderS
- sliderM
- sliderL
- sliderXL
```
___
#### Archives from contao-thememanager/ctm-estatemanager
```
- exposeHeaderArea
- exposeContentTopArea
- exposeContentArea
- exposeLeftArea
- exposeMainArea
- exposeRightArea
- exposeContentBottomArea
- exposeFooterArea
```

