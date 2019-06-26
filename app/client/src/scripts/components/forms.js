import { on } from 'delegated-events'
import Pristine from 'pristinejs'
import scrollTo from '../common/scrollTo'

const SELECTORS = Selectors({
  flyForm: 'form--fly',
  ajaxForm: 'form--ajax',
  formAnchor: '[data-form-anchor]',
  formScroll: '[data-form-scroll]',
  formErrorMessage: 'form-error-message',
  field: 'field',
  focused: 'is-focused',
  loading: 'is-loading'
})

const props = {
  $forms: document.querySelectorAll('form'),
  pristineConfig: {
    classTo: 'field',
    errorClass: 'has-error',
    successClass: 'has-success',
    errorTextParent: 'field',
    errorTextTag: 'div',
    errorTextClass: 'text-help'
  }
}

const fn = {
  init: () => {
    // Only setup any custom forms defined by our classes
    const customForms = [SELECTORS.flyForm, SELECTORS.ajaxForm]
    const $forms = Array.from(props.$forms)
      .filter(($f) => {
        // Handle cases where we wrap the form with an element (ex: SilverStripe forms)
        if ($f.closest(SELECTORS.asClass('flyForm'))) {
          return true
        }

        return customForms.some(x => $f.classList.contains(x))
      })

    if ($forms.length) {
      $forms.forEach($f => fn.setupForm($f))
      fn.setupFlyFields()
    }
  },

  setupForm: ($form) => {
    // Focus any filled in field on setup
    if ($form.classList.contains(SELECTORS.flyForm) || $form.closest(SELECTORS.asClass('flyForm'))) {
      const inputSelector = 'input:not([type=hidden]):not([type="checkbox"]):not([type="radio"])'
      Array.from($form.querySelectorAll(inputSelector))
        .filter($el => $el.value !== '' || $el === document.activeElement)
        .forEach($el => fn.handleFocus($el))

      Array.from($form.querySelectorAll('select'))
        .filter($el => $el.value !== '')
        .forEach($el => fn.handleFocus($el))
    }

    fn.setupValidation($form)

    // Setup file inputs
    $form.querySelectorAll('input[type=file]').forEach($el => {
      const $input = $el
      const $placeholder = document.createElement('div')
      $placeholder.classList.add('file-input-placeholder')

      if ($input.value) {
        $placeholder.textContent = $input.files[0].name
      }
      else {
        $placeholder.textContent = $input.placeholder
      }

      $input.parentNode.insertBefore($placeholder, $input)

      $placeholder.addEventListener('click', () => $input.click())
      $input.addEventListener('change', e => {
        $placeholder.textContent = e.target.files[0].name
        $input.closest('.field').classList.add('is-focused')
      })
    })
  },

  setupValidation: ($form) => {
    const allowScroll = $form.matches(SELECTORS.formScroll)
    const pristine = new Pristine($form, props.pristineConfig)

    // Revalidate easydropdown fields on change
    on('change',
      `${SELECTORS.asClass('flyForm')} select`,
      e => pristine.validate(e.target)
    )

    $form.addEventListener('submit', (e) => {
      const valid = pristine.validate()

      if (!valid) {
        e.preventDefault()
        const $firstError = $form.querySelector('.field.has-error')

        if ($firstError && allowScroll) {
          scrollTo($firstError, 150, () => $firstError.querySelector('input').focus())
        }

        return false
      }

      // Handle ajax submit if appropriate
      if ($form.classList.contains(SELECTORS.ajaxForm)) {
        e.preventDefault()

        const $errorMessage = $form.querySelector(SELECTORS.asClass('formErrorMessage'))
        $errorMessage.style.display = 'none'

        const $submitButton = $form.querySelector('[type=submit]')
        $submitButton.disabled = true
        $submitButton.classList.add(SELECTORS.loading)

        ajax.post($form.action, new FormData($form))
          .then(resp => {
            if (resp.data.success) {
              // Figure out where to scroll to
              let $anchor = $form.closest(SELECTORS.formAnchor)
              $anchor = $anchor ? $anchor : $form

              $form.innerHTML = `<div class="form-success-message">${resp.data.message}</div>`

              if (allowScroll) {
                scrollTo($anchor, 150)
              }
            } else {
              $errorMessage.innerHTML = resp.data.message
              $errorMessage.style.display = 'block'
            }
          })
          .catch(() => {
              $errorMessage.innerHTML = 'Sorry, there was a problem with your submission'
              $errorMessage.style.display = 'block'
          })
          .finally(() => {
            $submitButton.disabled = false
            $submitButton.classList.remove(SELECTORS.loading)
          })
      }

      return valid
    })
  },

  setupFlyFields: () => {
    on('focus',
      `${SELECTORS.asClass('flyForm')} input`,
      e => fn.handleFocus(e.target),
      { capture: true }
    )
    on('blur',
      `${SELECTORS.asClass('flyForm')} input, ${SELECTORS.asClass('flyForm')} select`,
      e => fn.handleBlur(e.target),
      { capture: true }
    ),
    on('change',
      `${SELECTORS.asClass('flyForm')} select`,
      e => fn.handleFocus(e.target),
      { capture: true }
    )
  },

  handleFocus: ($field) => {
    const $fieldHolder = $field.closest(SELECTORS.asClass('field'))

    if ($fieldHolder) {
      $fieldHolder.classList.add(SELECTORS.focused)
    }
  },

  handleBlur: ($field) => {
    const $fieldHolder = $field.closest(SELECTORS.asClass('field'))

    if ($fieldHolder && $field.value === '') {
      $fieldHolder.classList.remove(SELECTORS.focused)
    }
  }
}

export const Form = {
  init: ($form) => {
    fn.setupForm($form)
  }
}

export default {
  can: () => props.$forms.length,
  run: fn.init
}
