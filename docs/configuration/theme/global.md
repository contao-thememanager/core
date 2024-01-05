# Global

- [Basis-Einstellungen](#basis-einstellungen)
- [Farben](#farben)

## Basis-Einstellungen

In den Grundlegenden Einstellungen sind Optionen für die **Schriftgröße des Root-Elements** (`$browser-context`), die
**Hintergrundfarbe des Body-Elements** (`$body-bg`) und den **Border-Radius** (`$border-radius`) vorhanden.

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

### Primäre Farbe<sup>$primary</sup>

Hauptsächlich verwendet für Buttons, Komponenten, Icons oder Hervorhebungs-Backgrounds.

| SCSS       | CSS                                                     |
|------------|---------------------------------------------------------|
| `$primary` | `var(--clr-primary)`<br/>`var(--clr-primary-rgb)` (RGB) |

### Sekundäre Farbe<sup>$secondary</sup>

Hauptsächlich verwendet für alternative Buttons, Icons oder Hervorhebungs-Backgrounds.

| SCSS         | CSS                                                         |
|--------------|-------------------------------------------------------------|
| `$secondary` | `var(--clr-secondary)`<br/>`var(--clr-secondary-rgb)` (RGB) |

### Helle Hintergrundfarbe<sup>$light</sup>

Hauptsächlich verwendet für Hintergründe, um Artikel optisch zu trennen.

| SCSS     | CSS                                                 |
|----------|-----------------------------------------------------|
| `$light` | `var(--clr-light)`<br/>`var(--clr-light-rgb)` (RGB) |

### Dunkle Hintergrundfarbe<sup>$dark</sup>

Hauptsächlich verwendet für Hintergründe in Artikeln, die mit einer invertierten Textfarbe herausstechen sollen.

| SCSS    | CSS                                               |
|---------|---------------------------------------------------|
| `$dark` | `var(--clr-dark)`<br/>`var(--clr-dark-rgb)` (RGB) |
