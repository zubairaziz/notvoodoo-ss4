import { on } from 'delegated-events'
import 'lightgallery.js/dist/js/lightgallery.js'
import 'lg-thumbnail.js/dist/lg-thumbnail.js'
import 'lightgallery.js/dist/css/lightgallery.css'

const lightGallery = window.lightGallery

const SELECTORS = Selectors({
  gallery: '[data-photo-gallery]',
  galleryTrigger: 'photo-gallery__trigger',
  galleryPhoto: 'photo-gallery__photo'
})

const props = {
  $galleries: document.querySelectorAll(SELECTORS.gallery)
}

const fn = {
  init: () => {
    props.$galleries.forEach(el => {
      lightGallery(el, {
        selector: SELECTORS.asClass('galleryPhoto'),
        thumbnail: true,
        download: false
      })
    })

    fn.bindEvents()
  },

  bindEvents: () => {
    on('click', SELECTORS.asClass('galleryTrigger'), e => {
      const $gallery = e.currentTarget.closest(SELECTORS.gallery)

      $gallery.querySelectorAll(SELECTORS.asClass('galleryPhoto'))[0].click()
    })
  }
}

export default {
  can: () => props.$galleries.length,
  run: fn.init
}
