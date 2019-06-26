import tingle from 'tingle.js'
import { on } from 'delegated-events'
import { Form } from './forms'

const SELECTORS = Selectors({
  modals: '[data-modal]',
  modalLoading: 'modal--loading'
})

const props = {
  $modals: document.querySelectorAll(SELECTORS.modals)
}

const fn = {
  init: () => {
    on('click', SELECTORS.modals, fn.handleOpen)
  },

  handleOpen: (e) => {
    if (e.target.tagName !== 'A' || (e.target.tagName === 'A' && e.target.dataset.modal)) {
      e.preventDefault()
      fn.openModal(e.target.closest(SELECTORS.modals))
    }
  },

  openModal: ($el) => {
    let modal = null

    // Setup an image modal
    if ($el.dataset.modal === 'image') {
      let opts = {
        cssClass: ['modal--image']
      }

      modal = fn.buildImageModal($el.getAttribute('href'), opts)
    }

    // Setup a video modal
    if ($el.dataset.modal === 'video') {
      let opts = {
        cssClass: ['modal--video'],
        closeMethods: ['button', 'escape']
      }

      modal = fn.buildVideoModal($el.getAttribute('href'), opts)
    }

    // Setup a content/AJAX modal
    if ($el.dataset.modal === 'content') {
      let opts = {
        cssClass: ['modal--content', 'modal--ajax']
      }

      const href = $el.dataset.href ? $el.dataset.href : $el.getAttribute('href')
      modal = fn.buildContentModal(href, opts)
    }

    if (modal) {
      modal.open()
    }
  },

  buildModal: (opts = {}) => {
    let cssClass = ['modal']
    let closeMethods = ['button', 'escape', 'overlay']

    if (opts.hasOwnProperty('cssClass')) {
      cssClass = cssClass.concat(opts.cssClass)
    }

    if (opts.hasOwnProperty('closeMethods')) {
      closeMethods = opts.closeMethods
    }

    const modal = new tingle.modal({
      closeMethods,
      cssClass,
      beforeOpen: () => {
        modal.modal.classList.add(SELECTORS.modalLoading)

        // Move the close button
        const $closeBtn = modal.modal.querySelector('.tingle-modal__close')
        modal.modal.querySelector('.tingle-modal-box').appendChild($closeBtn)
      },
      onOpen: () => {
        modal.modal.classList.remove(SELECTORS.modalLoading)
      },
      onClose: () => {
        modal.destroy()
      }
    })

    return modal
  },

  buildImageModal: (href, opts = {}) => {
    const modal = fn.buildModal(opts)
    modal.setContent(`<img src="${href}">`)

    return modal
  },

  buildVideoModal: (href, opts = {}) => {
    const modal = fn.buildModal(opts)
    modal.setContent(`<iframe src="${href}?autoplay=1&rel=0" frameborder="0" allowfullscreen></iframe>`)

    return modal
  },

  buildContentModal: (href, opts = {}) => {
    const modal = fn.buildModal(opts)

    ajax.get(href, {
      responseType: 'text'
    }).then(resp => {
        modal.setContent(resp.data)
        modal.modal.classList.remove('modal--ajax')

        // Setup form if the content has one
        const $form = modal.modal.querySelector('form')

        if ($form) {
          Form.init($form)
          $form.querySelector('input').focus()
        }
      })

    return modal
  }
}

export default {
  can: () => props.$modals.length,
  run: fn.init
}
