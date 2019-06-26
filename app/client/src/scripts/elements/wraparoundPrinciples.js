import { on } from 'delegated-events'

const SELECTORS = Selectors({
  item: 'principles-item',
  trigger: 'principles-item__trigger'
})

const props = {
  $items: document.querySelectorAll(SELECTORS.asClass('item'))
}

const fn = {
  init: () => {
    fn.bindEvents()
  },

  bindEvents: () => {
    on('mouseover', SELECTORS.asClass('trigger'), fn.handleActivate)
    on('mouseout', SELECTORS.asClass('trigger'), fn.handleMouseOut)
    on('click', SELECTORS.asClass('trigger'), fn.handleActivate)
  },

  handleActivate: (e) => {
    if (window.matchMedia('(min-width: 768px)').matches) {
      document.querySelectorAll(`${SELECTORS.asClass('item')}.is-active`).forEach(el => el.classList.remove('is-active'))

      const $item = e.target.closest(SELECTORS.asClass('item'))
      $item.classList.add('is-active')
    }
  },

  handleMouseOut: (e) => {
    const $item = e.target.closest(SELECTORS.asClass('item'))
    $item.classList.remove('is-active')
  }
}

export default {
  can: () => props.$items.length,
  run: fn.init
}
