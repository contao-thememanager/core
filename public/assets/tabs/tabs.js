/**
 * Class to initialize tabs based on simple wrappers
 *
 * Options:
 *  - selector:             string                  Selector of the tab group
 *  - content:              string                  Selector of the tab content wrapper
 *  - cssClasses:           object                  Defines the CSS classes which will be set automatically.
 *    - active:             string                  CSS class for the active state.
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */

export default class Tabs
{
    tabs = []
    options = {
        selector: '[data-tab-list]',
        content:  '.tab-content>.inside',
        cssClasses: {
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
        const wrapper = list.querySelector(this.options.content)
        tabs.forEach(tab => wrapper.append(tab))
        tabs[0].classList.add(this.options.cssClasses.active)
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
            navs.forEach(i => i.classList.remove(active))

            tabs.forEach(i => {
                i.classList.remove(active)
                if (e.target.dataset.tabToggle === i.dataset.tabId)
                    i.classList.add(active)
            })

            e.target.parentElement.classList.add(active)
        }
    }
}
