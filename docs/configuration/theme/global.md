# Global

- [Basis-Einstellungen](#basis-einstellungen)
- [Farben](#farben)

## Basis-Einstellungen

In den Grundlegenden Einstellungen sind Optionen für die **Schriftgröße des Root-Elements** (`$browser-context`), die *
*Hintergrundfarbe des Body-Elements** (`$body-bg`) und den **Border-Radius** (`$border-radius`) vorhanden.

?> Die Variable **$browser-context** beeinflusst die Berechnung der im Framework enthaltenen Funktionen zur
Größenanpassung, sodass die Umwandlung von px zu rem immer im Kontext der Schriftgröße des Root-Elements erfolgt.

?> Die Variable **$border-radius** kann in einem Skin verwendet werden und hat derzeit keine weitere Funktion.
In [SCSS](https://sass-lang.com/) erfolgt die Verwendung über `$border-radius`, während in CSS
die [Custom-Property](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties) `var(--bdr-r)`
genutzt wird.

## Farben

Über die Theme-Konfiguration lassen sich die nachfolgenden 4 Farben einstellen, welche innerhalb der Konfiguration für
Buttons, Komponenten, Schriften und Überschriften verwendet werden. Zusätzlich stehen diese Farben als Icon- und
Hintergrundfarbe innerhalb von Seiten, Artikeln, Inhaltselementen, Modulen etc. zur Verfügung.

## Primäre Farbe ($primary)

Hauptsächlich verwendet für Buttons, Komponenten, Icons oder Hervorhebungs-Backgrounds.

| SCSS               | CSS                                                                   |
|--------------------|-----------------------------------------------------------------------|
| - Farbe `$primary` | - Farbe `var(--clr-primary)`<br/> - RGB-Wert `var(--clr-primary-rgb)` |

## Sekundäre Farbe ($secondary)

Hauptsächlich verwendet für alternative Buttons, Icons oder Hervorhebungs-Backgrounds.

| SCSS                 | CSS                                                                       |
|----------------------|---------------------------------------------------------------------------|
| - Farbe `$secondary` | - Farbe `var(--clr-secondary)`<br/> - RGB-Wert `var(--clr-secondary-rgb)` |

## Helle Hintergrundfarbe ($light)

Hauptsächlich verwendet für Hintergründe, um Artikel optisch zu trennen.

| SCSS             | CSS                                                               |
|------------------|-------------------------------------------------------------------|
| - Farbe `$light` | - Farbe `var(--clr-light)`<br/> - RGB-Wert `var(--clr-light-rgb)` |

## Dunkle Hintergrundfarbe ($dark)

Hauptsächlich verwendet für Hintergründe in Artikeln, die mit einer invertierten Textfarbe herausstechen sollen.

| SCSS            | CSS                                                             |
|-----------------|-----------------------------------------------------------------|
| - Farbe `$dark` | - Farbe `var(--clr-dark)`<br/> - RGB-Wert `var(--clr-dark-rgb)` |
