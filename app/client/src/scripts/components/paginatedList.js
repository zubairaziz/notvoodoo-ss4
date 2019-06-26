import { on } from 'delegated-events'
import scrollTo from '../common/scrollTo'

const SELECTORS = Selectors({
  paginatedList: '[data-paginated-list]',
  paginatedListNav: '[data-paginated-list-nav]',
  paginatedListTrigger: '[data-paginated-list-trigger]',
  paginatedListHide: '[data-paginated-list-hide]',
  paginatedCount: 'paginated-count',
  paginatedCountTarget: '[data-paginated-count-target]',
  loading: 'is-loading'
})

const props = {
  $paginated: document.querySelectorAll(SELECTORS.paginatedList),
  $paginatedCountTargets: document.querySelectorAll(SELECTORS.paginatedCountTarget)
}

const fn = {
  init: () => {
    fn.bindEvents()
  },

  bindEvents: () => {
    on('click', SELECTORS.paginatedListTrigger, fn.handleClick)
  },

  handleClick: (e) => {
    e.preventDefault()

    const $trigger = e.target
    const $nav = $trigger.closest(SELECTORS.paginatedListNav)
    const $list = $nav.previousElementSibling
    const isHide = $trigger.matches(SELECTORS.paginatedListHide)

    $trigger.disabled = true
    $trigger.classList.add(SELECTORS.loading)

    ajax.get($trigger.href)
      .then((resp) => {
        const $newNav = document.createRange().createContextualFragment(resp.data.nav)
        $nav.parentNode.replaceChild($newNav, $nav)

        if (isHide) {
          $list.innerHTML = resp.data.list
        } else {
          const $newList = document.createRange().createContextualFragment(resp.data.list)
          $list.append($newList)
        }

        // Update the new count elsewhere on the page, if applicable
        const $paginatedCount = document.querySelector(SELECTORS.asClass('paginatedCount'))

        props.$paginatedCountTargets.forEach(($el) => {
          $el.innerHTML = $paginatedCount.innerHTML
        })

        if (isHide) {
          scrollTo($list, 150)
        }
      })
  }
}

export default {
  can: () => props.$paginated.length,
  run: fn.init
}
