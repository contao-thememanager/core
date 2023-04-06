/**
 * Class to initialize tabs based on simple wrappers
 *
 * Options:
 *  - selector:             string                  Selectors of the tab group
 *  - cssClasses:           object                  Defines the CSS classes which will be set automatically.
 *    - wrapper:            string                  CSS class for the tab wrapper.
 *    - active:             string                  CSS class for the active state.
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */

export default class Tabs
{
    tabs = []
    options = {
        selector: '[data-tab-list]',
        cssClasses: {
            wrapper: 'tab-content',
            active:  'active'
        },
    }

    constructor(options = {})
    {
        this.options = Object.assign({}, this.options, options)

        this.navigations = document.querySelectorAll(this.options.selector)

        if (!this.navigations)
            return

        this._initTabs()
    }

    /**
     * Iterate through every found tab group on the page
     *
     * @private
     */
    _initTabs()
    {
        this.navigations.forEach((list) => {
            this._initTabGroup(list)
        });
    }

    /**
     * Initialize a tab group
     *
     * @param list
     * @private
     */
    _initTabGroup(list)
    {
        const group = list.dataset.tabList;
        const navs  = list.querySelectorAll('[data-tab-toggle]')
        const tabs  = document.querySelectorAll(`[data-tab-group=${group}]`)

        this._buildStructure(list, tabs)

        navs.forEach(nav => this._bindEvents(nav, navs, tabs))
    }

    /**
     * Build the wrapper
     *
     * @param list
     * @param tabs
     * @private
     */
    _buildStructure(list, tabs)
    {
        const outer = document.createElement('div')
        outer.classList.add(this.options.cssClasses.wrapper)
        tabs.forEach(tab => outer.append(tab))
        list.firstElementChild.appendChild(outer)
    }

    /**
     * Bind the tab events for the buttons and their content
     *
     * @param nav
     * @param navs
     * @param tabs
     * @private
     */
    _bindEvents(nav, navs, tabs)
    {
        const active = this.options.cssClasses.active

        nav.onclick = e => {
            navs.forEach(i => i.parentElement.classList.remove(active))

            tabs.forEach(i => {
                i.classList.remove(active)
                if (e.target.dataset.tabToggle === i.dataset.tabId)
                    i.classList.add(active)
            })

            e.target.parentElement.classList.add(active)
        }
    }
}
