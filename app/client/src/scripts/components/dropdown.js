import easydropdown from 'easydropdown'

const SELECTORS = Selectors({
  dropdown: 'js-dropdown',
  navDropdown: 'js-navigation-dropdown',
  submitDropdown: 'js-submit-dropdown'
})

const props = {
  $dropdowns: document.querySelectorAll(SELECTORS.asClass('dropdown'))
}

const fn = {
  init: () => {
    props.$dropdowns.forEach(($el) => {
      if (!$el.querySelector('[selected]')) {
        $el.selectedIndex = -1
      }

      fn.setupDropdown($el)
    })
  },

  setupDropdown: ($el) => {
    if ($el.tagName !== 'SELECT') {
      return
    }

    const opts = {
      behavior: {
        openOnFocus: true,
        maxVisibleItems: 8,
        liveUpdates: true
      }
    }

    if ($el.classList.contains(SELECTORS.navDropdown)) {
      opts.callbacks = {
        onSelect: value => window.location.href = value
      }
    }

    if ($el.classList.contains(SELECTORS.submitDropdown)) {
      opts.callbacks = {
        onSelect: () => $el.closest('form').submit()
      }
    }

    if ($el.required) {
      const $placeholder = $el.querySelector('option[value=""]')

      if ($placeholder) {
        $placeholder.setAttribute('data-placeholder', true)
      }
    }

    easydropdown($el, opts)

    if ($el.value !== '') {
      const $holder = $el.closest('.styled-dropdown--form')

      if ($holder) {
        $holder.classList.add('is-focused')
      }
    }
  }
}

export const StyledDropdown = {
  init: ($el) => {
    fn.setupDropdown($el)
  }
}

export default {
  can: () => props.$dropdowns.length,
  run: fn.init
}
