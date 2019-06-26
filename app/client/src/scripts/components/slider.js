import throttle from 'lodash/throttle'
import Flickity from 'flickity'
import 'flickity-sync'
import 'flickity-fade'
import 'flickity/dist/flickity.css'
import 'flickity-fade/flickity-fade.css'

export const ARROW_SHAPE = 'M52.806 98.977a3.52 3.52 0 0 0 0-4.996L8.497 50.048 52.806 6.03a3.52 3.52 0 0 0 0-4.996 3.575 3.575 0 0 0-5.03 0L1.042 47.464A3.439 3.439 0 0 0 0 49.962c0 .862.347 1.81 1.04 2.498l46.737 46.43a3.5 3.5 0 0 0 5.029.087z'

export const SLIDER_DEFAULTS = {
  autoPlay: 6000,
  adaptiveHeight: true,
  pauseAutoPlayOnHover: false,
  cellAlign: 'left',
  arrowShape: ARROW_SHAPE,
  draggable: false
}

const SELECTORS = Selectors({
  slider: '[data-slider]',
  sliderNavTop: 'slider--nav-top',
  sliderDots: 'flickity-page-dots'
})

const props = {
  $sliders: document.querySelectorAll(SELECTORS.slider)
}

const fn = {
  init: () => {
    props.$sliders.forEach(($el) => {
      let opts = Object.assign({}, SLIDER_DEFAULTS)

      if ($el.dataset.sliderGrouped) {
        opts = fn.handleGroupCells(opts)
      }

      if ($el.dataset.sliderAutoplay) {
        opts.autoPlay = false
      }

      if ($el.dataset.sliderFade) {
        opts.fade = true
      } else {
        opts.wrapAround = true
      }

      if ($el.dataset.sliderArrows) {
        opts.prevNextButtons = ($el.dataset.sliderArrows === 'true')
      }

      if ($el.dataset.sliderDots) {
        opts.pageDots = ($el.dataset.sliderDots === 'true')
      }

      fn.setupSlider($el, opts)
    })

    fn.bindEvents()
    setTimeout(fn.handleResize, 500)
  },

  bindEvents: () => {
    window.addEventListener('resize', throttle(fn.handleResize, 250))
  },

  handleResize: () => {
    props.$sliders.forEach(($el) => {
      const slider = Flickity.data($el)

      if ($el.dataset.sliderGrouped) {
        slider.options = fn.handleGroupCells(slider.options)
      }

      slider.resize()
      slider.reposition()
    })
  },

  handleGroupCells: (_opts) => {
    let opts = Object.assign({}, _opts)
    opts.groupCells = undefined
    opts.fade = false

    if (window.matchMedia('(min-width: 768px)').matches) {
      opts.groupCells = '100%'
    }

    return opts
  },

  setupSlider: ($el, opts = {}) => {
    const slider = new Flickity($el, opts)

    if ($el.classList.contains(SELECTORS.sliderNavTop)) {
      const $dots = $el.querySelector(SELECTORS.asClass('sliderDots'))

      if ($dots) {
        $el.prepend($dots)
      }
    }

    fn.updateSlider(slider)

    slider.on('resize', () => {
      fn.updateSlider(slider)
    })
  },

  updateSlider: (slider) => {
    const isSingleSlide = slider.slides.length < 2
    const hasPrevNextButtons = slider.options.prevNextButtons

    slider.element.classList.toggle('is-single-slide', isSingleSlide)
    slider.element.classList.toggle('has-buttons', hasPrevNextButtons)
  }
}

export const Slider = {
  init: ($el, opts = {}) => {
    fn.setupSlider($el, opts)
  }
}

export default {
  can: () => props.$sliders.length,
  run: fn.init
}
