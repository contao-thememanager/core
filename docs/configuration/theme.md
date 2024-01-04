# Theme konfigurieren

# Theme konfigurieren

Innerhalb der Theme-Konfiguration können sämtliche Variablen des CTM-Frameworks überschrieben werden, um das
Erscheinungsbild jeder Webseite nach Bedarf anzupassen.

In Version 2.0 des Contao-ThemeManagers wurde die Konfiguration angepasst, sodass die hier dokumentierte Reihenfolge der
Theme-Konfiguration mit der im Contao-Backend übereinstimmt.

!> Jeder Bezeichner einer einzustellenden Variable entspricht der
jeweiligen [SCSS-Variable](https://sass-lang.com/guide/#variables), wodurch der Wert im [Skin](/configuration/skin.md)
abgerufen werden kann.

___

## Verfügbare Kapitel

- [Global](#global)
- [Schriften](#schriften)
- [Überschriften](#überschriften)
- [Buttons](#buttons)
- [Komponenten](#komponenten)
- [Formular](#formular)
- [Module](#module)
- [Header](#header)
- [Sonstiges](#sonstiges)
- [Layout](#layout)

### Global

### Global

Die globalen Einstellungen betreffen grundlegende Aspekte wie Farben und Root-Schriftgröße.

- [Mehr über globale Einstellungen erfahren](/configuration/theme/global.md)

### Schriften

In diesem Abschnitt können die Typografie sowie Links angepasst werden.

- [Einstellungen zu Schriften vornehmen](/configuration/theme/fonts.md)

### Überschriften

Hier erfolgt die Konfiguration von Schriftgrößen, Farben und weiteren Optionen für Überschriften (*h1* - *h6*).

- [Weitere Informationen zu Überschriften](/configuration/theme/headings.md)

### Buttons

Änderungen am Erscheinungsbild von Buttons und Links, für die der Button-Stil über
den [StyleManager](/tools/style-manager.md) festgelegt wurde.

- [Anleitung zur Konfiguration von Buttons](/configuration/theme/buttons.md)

### Komponenten

Dieser Bereich ermöglicht die Anpassung des Aussehens von Cards (Box), Linien, Tabellen und der Icon-Komponente.

- [Stilanpassungen für ThemeManager-Komponenten](/configuration/theme/components.md)

### Formular

Über die Formular-Konfiguration kann das gesamte Erscheinungsbild von `form`, `select`, `input`, `textarea`, `label`,
und `legend` festgelegt werden.

- [Anleitung zur Konfiguration von Formularstilen](/configuration/theme/components.md)

### Module

Einstellungen für Navigationen und die Breadcrumb-Navigation.

- [Konfiguration von Modul-Stilen](/configuration/theme/components.md)

### Header

Dieser Abschnitt behandelt die Konfiguration des `<header>`-Elements.

- [Anpassung des Header-Layouts](/configuration/theme/header.md)

### Sonstiges

Einstellungen für Textauswahl-Farbe, Schatten und Seitenverhältnisse von Bildern.

- [Sonstige Anpassungen](/configuration/theme/miscellaneous.md)

### Layout

Alle Einstellungen, die das Layout des Frameworks beeinflussen. Hierzu gehören Breakpoints, das responsive Verhalten,
Artikel-Abstände, das Grid, Paddings sowie Margins.

- [Einfussnahme auf das Layout der Website](/configuration/theme/layout.md)
