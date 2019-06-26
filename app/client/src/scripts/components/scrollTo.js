import { on } from 'delegated-events'
import scrollTo from '../common/scrollTo'

const SELECTORS = Selectors({
  scroll: '[data-scroll]',
  scrollToMe: '[data-scrolltome]'
})

const fn = {
  init: () => {
    fn.bindEvents()
    fn.handleUrl()
    fn.handleScrollToMe()
  },

  bindEvents: () => {
    on('click', SELECTORS.scroll, fn.handleClick)
  },

  handleClick: (e) => {
    e.preventDefault()

    const $el = e.target.closest(SELECTORS.scroll)

    let targetSelector = null

    if ($el.dataset.scroll) {
      targetSelector = $el.dataset.scroll
    } else if ($el.tagName === 'A') {
      targetSelector = $el.hash
    } else {
      return
    }

    const $target = document.querySelector(targetSelector)

    if ($target) {
      scrollTo($target)
    }
  },

  handleUrl: () => {
    if (window.location.hash) {
      const $target = document.querySelector(window.location.hash)

      if ($target) {
        scrollTo($target)
      }
    }
  },

  // Automatically scrolls to the first element with the [data-scrolltome] attribute
  handleScrollToMe: () => {
    const $target = document.querySelector(SELECTORS.scrollToMe)

    if ($target) {
      setTimeout(() => {
        scrollTo($target)
      }, 250)
    }
  }
}

export default {
  can: () => true,
  run: fn.init
}
