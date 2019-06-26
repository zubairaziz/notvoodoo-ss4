import Flickity from 'flickity'
import { ARROW_SHAPE } from '../components/slider'

const SELECTORS = Selectors({
  storiesHeaderImages: 'stories-header-images',
  storiesHeaderContent: 'stories-header-content',
  otherStories: 'story-other-stories',
  otherStoriesCounter: 'story-other-stories__counter'
})

const props = {
  $storiesHeaderImages: document.querySelector(SELECTORS.asClass('storiesHeaderImages')),
  $storiesHeaderContent: document.querySelector(SELECTORS.asClass('storiesHeaderContent')),
  $otherStories: document.querySelector(SELECTORS.asClass('otherStories')),
  $otherStoriesCounter: document.querySelector(SELECTORS.asClass('otherStoriesCounter'))
}

const fn = {
  init: () => {
    if (props.$storiesHeaderImages) {
      fn.setupSliders()
    }

    if (props.$otherStories) {
      fn.setupOtherStories()
    }
  },

  setupSliders: () => {
    new Flickity(props.$storiesHeaderImages, {
      autoPlay: 6000,
      pauseAutoPlayOnHover: false,
      cellAlign: 'left',
      draggable: false,
      setGallerySize: false,
      prevNextButtons: false,
      pageDots: false,
      fade: true
    })

    new Flickity(props.$storiesHeaderContent, {
      sync: props.$storiesHeaderImages,
      pauseAutoPlayOnHover: false,
      cellAlign: 'left',
      draggable: false,
      prevNextButtons: false,
      fade: true
    })
  },

  setupOtherStories: () => {
    const otherStories = new Flickity(props.$otherStories.querySelector('.story-other-stories__slider'), {
      autoPlay: 6000,
      cellAlign: 'left',
      draggable: false,
      pageDots: false,
      wrapAround: true,
      arrowShape: ARROW_SHAPE
    })

    otherStories.on('change', index => {
      let count = 6

      if (index > 1) {
        count = (index - 1) * 6
      }

      if (index > 0) {
        count += otherStories.selectedElement.querySelectorAll('.story-other-stories__block').length
      }

      props.$otherStoriesCounter.querySelector('strong').textContent = count
    })

    setTimeout(() => {
      otherStories.resize()
      otherStories.reposition()
    }, 250)
  }
}

export default {
  can: () => document.body.classList.contains('pagetype-storypage'),
  run: fn.init
}
