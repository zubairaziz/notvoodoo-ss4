import { on } from 'delegated-events'
import requestTimeout from '../common/requestTimeout'

const SELECTORS = Selectors({
  toggle: '[data-toggle]',
  active: 'is-active',
  opened: 'is-opened',
  trigger: 'js-toggle-trigger'
})

const props = {
  $toggles: document.querySelectorAll(SELECTORS.toggle)
}

const fn = {
  init: () => {
    fn.bindEvents()
  },

  bindEvents: () => {
    on('click', SELECTORS.toggle, fn.handleClick)
  },

  handleClick: (e) => {
    e.preventDefault()

    const $trigger = e.target.closest(SELECTORS.toggle)
    const $target = document.querySelector($trigger.dataset.toggleTarget)

    if ($trigger && $target) {
      fn.toggle($trigger, $target)
    }
  },

  expand: ($trigger, $target) => {
    fn.handleGroup($trigger)

    const $originalTrigger = fn.getOriginalTrigger($trigger)

    if ($originalTrigger) {
      $originalTrigger.classList.add(SELECTORS.active)
    }

    $target.classList.add(SELECTORS.active)
    $target.style.height = `${$target.scrollHeight}px`

    requestTimeout(() => {
      $target.classList.add(SELECTORS.opened)
    }, 400)
  },

  collapse: ($trigger, $target) => {
    const $originalTrigger = fn.getOriginalTrigger($trigger)

    if ($originalTrigger) {
      $originalTrigger.classList.remove(SELECTORS.active)
    }

    $target.classList.remove(SELECTORS.active)
    $target.classList.remove(SELECTORS.opened)
    $target.style.height = 0

    requestTimeout(() => {
      $target.style.removeProperty('height')
    }, 350)
  },

  toggle: ($trigger, $target) => {
    $target.classList.contains(SELECTORS.active)
      ? fn.collapse($trigger, $target)
      : fn.expand($trigger, $target)
  },

  // Allow other elements to trigger toggle but we always have the original to apply the active class to
  getOriginalTrigger: ($trigger) => {
    return document.querySelector(`[data-toggle-target="${$trigger.dataset.toggleTarget}"]${SELECTORS.asClass('trigger')}`)
  },

  handleGroup: ($trigger) => {
    // Allow only one element within the toggle-group to be expanded at a time
    const toggleGroup = $trigger.dataset.toggleGroup

    if (toggleGroup) {
      const $expanded = document.querySelectorAll(`[data-toggle-group="${toggleGroup}"]${SELECTORS.asClass('active')}`)

      $expanded.forEach(($trigger) => {
        const $target = document.querySelector($trigger.dataset.toggleTarget)
        fn.collapse($trigger, $target)
      })
    }
  }
}

export default {
  can: () => props.$toggles.length,
  run: fn.init
}
