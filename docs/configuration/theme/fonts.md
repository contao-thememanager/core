# Schriften

- [Typografie](#typografie)
- [Links](#links)

## Typografie

Die Typografie-Einstellungen erlauben die Einstellung der Schriftart ($font-family), der Schriftgröße des `<body>`, der
Schriftstärken und der Schriftfarben (regulär und invertiert). Die invertierte Schriftfarbe lässt sich in Artikeln und
Inhaltselementen über den Reiter Text einstellen und überschreibt die normale Farbe.

Schriftgröße, Art, Zeilenhöhe etc. haben immer auch Einfluss auf alle weiteren Elemente. Lediglich Überschriften und
Formulare können individuell eingestellt werden.

### Schriftart<sup>$font-family-base</sup>

Die Einstellung überschreibt die Schriftart der Webseite und kann mit mehreren Schriftarten kombiniert werden.

> Sofern es sich nicht um eine Standard-Schriftart handelt, muss die Schrift zusätzlich im Seitenlayout eingebunden
> werden. Zwecks Ladezeiten-Optimierung empfiehlt sich eine lokale Einbindung der Schriftarten.

| SCSS           | CSS                  |
|----------------|----------------------|
| `$font-family` | `var(--font-family)` |

### Schriftgröße<sup>$font-size-base</sup>

Beeinflusst die Schriftgröße für normale Texte einer Webseite und kann in px und rem angegeben werden.

| SCSS              | CSS              |
|-------------------|------------------|
| `$font-size-base` | `var(--fs-base)` |

### Zeilenhöhe<sup>$line-height-base</sup>

Dient der Einstellung der vertikalen Zeilenhöhe von Schriften

| SCSS                | CSS                   |
|---------------------|-----------------------|
| `$line-height-base` | `var(--leading-base)` |

### Schriftfarben<sup><span>$text-color-regular</span><span>$text-color-invert</span></sup>

Der ThemeManager liefert grundsätzlich zwei Farben für normalen Text, welche über die StyleManager-Einstellungen
ausgewählt werden können.
Sowohl die reguläre, als auch die invertierte Farbe, sind in den Standard-Einstellungen der Konfiguration in den meisten
Komponenten mit diesen Farben vorbelegt.

> Die StyleManager-Einstellung der Text-Farbe hat zusätzlichen Einfluss auf andere Farben wie Überschriften, Buttons,
> Formularen etc. und kontrolliert nicht nur die Text-Farbe.

| SCSS                  | CSS                      |
|-----------------------|--------------------------|
| `$text-color-regular` | `var(--text-clr-base)`   |
| `$text-color-invert`  | `var(--text-clr-invert)` |

### Schriftstärken<sup><span>$font-weight-base</span><span>$font-weight-*</span><span>$strong-font-weight</span></sup>

Die Standard-Schriftstärke kann über **$font-weight-base**, die <strong>-Schriftstärke über $strong-font-weight
festgelegt werden. Restliche Schriftstärken werden lediglich in der Konfiguration verwendet.

!> Sofern keine Standard-Schriftart eingestellt wurde, müssen die Schriftstärken der Schriftart eingebunden werden,
damit der Effekt sichtbar wird.

| SCSS                   | CSS                  |
|------------------------|----------------------|
| `$font-weight-base`    | `var(--fw-base)`     |  
| `$font-weight-light`   | `var(--fw-light)`    |  
| `$font-weight-regular` | `var(--fw-regular)`  |  
| `$font-weight-medium`  | `var(--fw-medium)`   |  
| `$font-weight-semibol` | `var(--fw-semibold)` |  
| `$font-weight-bold`    | `var(--fw-bold)`     |  

## Links

