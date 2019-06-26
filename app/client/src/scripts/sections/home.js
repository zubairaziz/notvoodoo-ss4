import tingle from 'tingle.js'
import Cookies from 'js-cookie'

const SELECTORS = Selectors({
  overlay: '[data-overlay]'
})

const props = {
  $overlay: document.querySelector(SELECTORS.overlay)
}

const fn = {
  init: () => {
    if (props.$overlay) {
      fn.setupOverlay()
    }
  },

  setupOverlay: () => {
    const overlayUrl = props.$overlay.dataset.overlay

    ajax.get(overlayUrl, {
      responseType: 'text'
    }).then(resp => {
      const modal = new tingle.modal({
        closeMethods: ['button', 'escape'],
        cssClass: ['modal', 'modal--content'],
        beforeOpen: () => {
          // Move the close button
          const $closeBtn = modal.modal.querySelector('.tingle-modal__close')
          modal.modal.querySelector('.tingle-modal-box').appendChild($closeBtn)

          const inOneHour = 1/24
          Cookies.set('seenhomepageoverlay', true, { expires: inOneHour })
        },
        onClose: () => {
          modal.destroy()
        }
      })

      modal.setContent(resp.data)
      modal.open()

      setTimeout(() => {
        modal.checkOverflow()
      }, 100)
    })
  }
}

export default {
  can: () => document.body.classList.contains('pagetype-homepage'),
  run: fn.init
}
