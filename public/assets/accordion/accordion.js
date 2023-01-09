/**
 * Class to be able to treat different structures as accordion.
 *
 * Options:
 *  - selectors:            string[]                Selectors of the elements which should be treated as accordion.
 *  - duration:             integer                 Defines the time (ms) used to expand and collapse the element. If 0, no animation will be performed.
 *  - easing:               function                Defines the easing of the animation.
 *  - cssClasses:           object                  Defines the CSS classes which will be set automatically.
 *    - handle:             string                  CSS class for the accordion handle.
 *    - content:            string                  CSS class for the accordion content.
 *    - open:               string                  CSS class which is set when the content is expanded.
 * - detectors:             object[]                Enables the addition of custom detectors to support other structures.
 *
 * Detector-Structure:
 * {
 *     match:               function                Verification that the current markup being checked matches the structure to provide an accordion. The accordion element is passed as a parameter.
 *     scheme:              function                A method which returns the schema. The accordion element is passed as a parameter.
 * }
 *
 * Scheme-Structure:
 * {
 *     mode:                integer                 The behaviour of the accordion. (MULTI_CLOSED: 0, MULTI_OPENED: 1, SINGLE_CLOSED: 2, SINGLE_OPENED: 3, MULTI: 4, SINGLE: 5)
 *     container:           HTMLElement             The accordion container.
 *     collapsible:         object[]                All child elements, which can be expanded and collapsed.
 *     [
 *         {
 *             handle:      HTMLElement             The handle element, which act to expand and collapse.
 *             content      HTMLElement             The element that is expanded and collapsed.
 *             collapsed:   boolean                 The current status, whether the element should be already open or closed.
 *         }
 *         ...
 *     ]
 * }
 *
 * @author Daniele Sciannimanica
 */
export default class Accordion
{
    accordions = []
    schemes = []
    options = {
        selectors: ['.ctm_accordion'],
        duration: 300,
        easing: Easing.easeOutQuad,
        cssClasses: {
            handle: 'ctm-accordion-handle',
            content: 'ctm-accordion-content',
            open: 'open'
        },
        detectors: []
    }

    constructor(options = {})
    {
        this.options = Object.assign({}, this.options, options)
        this.options.animated = this.options.duration > 0

        this.detectAccordion()

        let resizeTimeout = {}

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => this.update(), 100);
        })
    }

    /**
     * Detect all accordions on current page.
     */
    detectAccordion()
    {
        for(const selector of this.options.selectors)
        {
            const accordions = document.querySelectorAll(selector)

            if(!accordions.length)
            {
                continue
            }

            for(const accordion of accordions)
            {
                const scheme = this.detectMarkup(accordion)

                if(null === scheme)
                {
                    continue
                }

                this.init(scheme)
                this.schemes.push(scheme)
            }
        }
    }

    /**
     * Searches for given markups which can be used as an accordion and returns a corresponding scheme.
     * Passes first through the standard detectors and then through those that have been passed.
     *
     * @param accordion
     */
    detectMarkup(accordion)
    {
        if(this.accordions.includes(accordion))
        {
            return null
        }

        // Check contao accordion markup
        if(accordion.querySelector('.toggler + .accordion'))
        {
            let mode = AccordionMode.MULTI

            const group = accordion.dataset.accordionGroup
            const collection = []

            const scheme = (acc, collapsed) => {
                // Get collapsable content
                const content = acc.querySelector('.accordion')

                // Check previous state; Should one be given, take this one, because then it is an update
                const isCollapsed = typeof content?.accordion?.prevCollapsed !== 'undefined' ? content.accordion.prevCollapsed : collapsed

                // Return scheme
                return {
                    handle: acc.querySelector('.toggler'),
                    content: content,
                    collapsed: isCollapsed
                }
            }

            if(group)
            {
                let isOpen = false
                const siblings = document.querySelectorAll(`[data-accordion-group="${group}"]`)

                if(siblings.length > 1)
                {
                    [...siblings].map((acc) => {
                        // Detect collapsable state
                        let collapsed = !parseInt(acc.dataset.accordionOpen)

                        // Add collected accordion
                        this.accordions.push(acc)

                        // Add state to detect mode
                        if(isOpen)
                        {
                            collapsed = true
                        }
                        else if(!isOpen && !collapsed)
                        {
                            isOpen = true
                        }

                        // Return collection of each accordion
                        collection.push(scheme(acc, collapsed))
                    })

                    // Set mode
                    mode = AccordionMode.SINGLE
                }
                else
                {
                    collection.push(scheme(accordion, !parseInt(accordion.dataset.accordionOpen)))
                }
            }
            else{
                collection.push(scheme(accordion, !parseInt(accordion.dataset.accordionOpen)))
            }

            return {
                mode: mode,
                container: accordion,
                collapsible: collection
            }
        }

        // Check possible accordion elements of the ThemeManager
        if(accordion.querySelector('.c_list'))
        {
            const mode = this.getMode(accordion.querySelector('[data-accordion-mode]')?.dataset.accordionMode)

            return {
                mode: mode,
                container: accordion,
                collapsible: [...accordion.querySelectorAll('.c_list > div')].map((part, index) => {
                    // Get collapsable content
                    const content = part.querySelector('.content')

                    // Check previous state; Should one be given, take this one, because then it is an update
                    const isCollapsed = typeof content?.accordion?.prevCollapsed !== 'undefined' ? content.accordion.prevCollapsed : this.isCollapsed(mode, index)

                    // Return scheme
                    return {
                        handle: part.querySelector('.toggler'),
                        content: content,
                        collapsed: isCollapsed
                    }
                })
            }
        }

        // Custom detectors
        if(this.options.detectors.length)
        {
            for(const detector of this.options.detectors)
            {
                if(detector?.match.call(this, accordion, this))
                {
                    return detector.scheme.call(this, accordion, this)
                }
            }
        }

        return null
    }

    /**
     * Initialize an accordion by a scheme.
     *
     * @param scheme
     */
    init(scheme)
    {
        for(const part of scheme.collapsible)
        {
            // Set content properties
            part.content.accordion = {
                actualHeight: this.options.animated ? this.getActualHeight(part.content) : 'auto'
            }

            // Set default css classes
            part.handle.classList.add(this.options.cssClasses.handle)
            part.content.classList.add(this.options.cssClasses.content)

            // Set default state
            this.collapse(part, part.collapsed, false)

            // Set click event
            part.handle.addEventListener('click', part.handle.fn = () => {
                // Save state before close all other
                const state = !part.content.accordion.collapsed

                // Close all other on mode `SINGLE`
                if(
                    AccordionMode.SINGLE_OPENED === scheme.mode ||
                    AccordionMode.SINGLE_CLOSED === scheme.mode ||
                    AccordionMode.SINGLE === scheme.mode
                ){
                    this.closeAll(scheme)
                }

                this.collapse(part, state)
            })
        }
    }

    /**
     * Destroy all accordion.
     */
    destroy()
    {
        for(const scheme of this.schemes)
        {
            for(const part of scheme.collapsible)
            {
                // Remove default css classes
                part.handle.classList.remove(this.options.cssClasses.handle)
                part.handle.classList.remove(this.options.cssClasses.open)
                part.content.classList.remove(this.options.cssClasses.content)
                part.content.classList.remove(this.options.cssClasses.open)

                // Remove height
                part.content.style.height = ''

                // Set old state
                part.content.accordion.prevCollapsed = part.content.accordion.collapsed

                // Remove click event
                part.handle.removeEventListener('click', part.handle.fn)
            }
        }

        this.schemes = []
        this.accordions = []
    }

    /**
     * Update schemes.
     */
    update()
    {
        this.destroy()
        this.detectAccordion()
    }

    /**
     * Collapse / unfold accordion part.
     *
     * @param part
     * @param collapsed
     * @param animated
     */
    collapse(part, collapsed, animated = true)
    {
        // Skip if the status to be set is already set
        if(part.content.accordion.collapsed === collapsed)
            return

        // Set new state
        part.content.accordion.collapsed = collapsed

        // Set css classes
        part.handle.classList.toggle(this.options.cssClasses.open, !collapsed)
        part.content.classList.toggle(this.options.cssClasses.open, !collapsed)

        if(animated && this.options.animated)
            this.animate(part, collapsed)
        else
            part.content.style.height = collapsed ? 0 : part.content.accordion.actualHeight
    }

    /**
     * Animate collapsable.
     *
     * @param part
     * @param collapse
     */
    animate(part, collapse)
    {
        let height = part.content.accordion.actualHeight.toString().replace('px', '')
        let start = performance.now();

        const anim = (time) => {
            let timeFraction = (time - start) / this.options.duration

            if(timeFraction < 0)
                timeFraction = 0
            else if (timeFraction > 1)
                timeFraction = 1

            let progress = this.options.easing(timeFraction)
            let newHeight = Math.ceil(progress * height);

            if(collapse)
                newHeight = (newHeight - height) * -1

            part.content.style.height = newHeight + 'px'

            if (timeFraction < 1)
                requestAnimationFrame(anim)
        }

        requestAnimationFrame(anim);
    }

    /**
     * Check if an accordion item is collapsed.
     *
     * @param mode
     * @param index
     */
    isCollapsed(mode, index)
    {
        switch (mode)
        {
            case AccordionMode.SINGLE_OPENED: return index !== 0
            case AccordionMode.SINGLE_CLOSED: return true
            case AccordionMode.MULTI_OPENED:  return false
            case AccordionMode.MULTI_CLOSED:  return true
            default: return true
        }
    }

    /**
     * Get mode by string.
     *
     * @param str
     */
    getMode(str)
    {
        if(null === str || !str.trim())
        {
            return AccordionMode.SINGLE_OPENED
        }

        switch (str)
        {
            case 'single_open':     return AccordionMode.SINGLE_OPENED
            case 'single_closed':   return AccordionMode.SINGLE_CLOSED
            case 'multi_open':      return AccordionMode.MULTI_OPENED
            case 'multi_closed':    return AccordionMode.MULTI_CLOSED

            case 'single':          return AccordionMode.SINGLE
            case 'multi':           return AccordionMode.MULTI
        }
    }

    /**
     * Returns the actual height of an element.
     *
     * @param element
     */
    getActualHeight(element)
    {
        // set temporary border to get real height
        element.style.border = '.02px solid transparent'
        const elHeight = element.clientHeight + 'px'
        element.style.border = null

        return elHeight
    }

    /**
     * Close all collapsible by scheme.
     *
     * @param scheme
     */
    closeAll(scheme)
    {
        for(const part of scheme.collapsible)
            this.collapse(part, true)
    }
}

/**
 * Accordion modes.
 *
 * @type {{MULTI_OPENED: number, SINGLE: number, SINGLE_OPENED: number, SINGLE_CLOSED: number, MULTI_CLOSED: number, MULTI: number}}
 */
export const AccordionMode = {
    MULTI_CLOSED: 0,
    MULTI_OPENED: 1,
    SINGLE_CLOSED: 2,
    SINGLE_OPENED: 3,

    MULTI: 4,
    SINGLE: 5
};

/**
 * Predefined easing methods.
 *
 * @type {{easeOutCubic: (function(*): *), linear: (function(*): *), easeOutQuint: (function(*): *), easeInQuart: (function(*): *), easeInOutQuint: (function(*): number), easeInQuad: (function(*): *), easeOutQuart: (function(*): *), easeInCubic: (function(*): *), easeInQuint: (function(*): *), easeOutQuad: (function(*): *), easeInOutQuad: (function(*): number), easeInOutCubic: (function(*): number), easeInOutQuart: (function(*): number)}}
 */
export const Easing = {
    linear: t => t,
    easeInQuad: t => t*t,
    easeOutQuad: t => t*(2-t),
    easeInOutQuad: t => t<.5 ? 2*t*t : -1+(4-2*t)*t,
    easeInCubic: t => t*t*t,
    easeOutCubic: t => (--t)*t*t+1,
    easeInOutCubic: t => t<.5 ? 4*t*t*t : (t-1)*(2*t-2)*(2*t-2)+1,
    easeInQuart: t => t*t*t*t,
    easeOutQuart: t => 1-(--t)*t*t*t,
    easeInOutQuart: t => t<.5 ? 8*t*t*t*t : 1-8*(--t)*t*t*t,
    easeInQuint: t => t*t*t*t*t,
    easeOutQuint: t => 1+(--t)*t*t*t*t,
    easeInOutQuint: t => t<.5 ? 16*t*t*t*t*t : 1+16*(--t)*t*t*t*t
}
