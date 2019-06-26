import requestTimeout from '../common/requestTimeout'
import throttle from 'lodash/throttle'
import debounce from 'lodash/debounce'
import { on } from 'delegated-events'

const SELECTORS = Selectors({
  header: 'site-header',
  bodyOffset: 'site-header--body-offset',
  searchOpenTrigger: 'site-search-trigger',
  searchCloseTrigger: 'site-search-close',
  searchForm: 'site-search__form',
  htmlSearchActive: 'js-search-active'
})

const props = {
  $header: document.querySelector(SELECTORS.asClass('header')),
  $searchForm: document.querySelector(SELECTORS.asClass('searchForm')),
  headerHeight: 0,
  needsBodyOffset: false,
  stuck: false
}

const fn = {
  init: () => {
    props.headerHeight = props.$header.offsetHeight
    props.needsBodyOffset = props.$header.classList.contains(SELECTORS.bodyOffset)

    fn.handleScroll()
    fn.bindEvents()
  },

  bindEvents: () => {
    window.addEventListener('scroll', throttle(fn.handleScroll, 50))
    window.addEventListener('resize', debounce(fn.handleResize, 250))
    on('click', SELECTORS.asClass('searchOpenTrigger'), fn.handleOpenSearch)
    on('click', SELECTORS.asClass('searchCloseTrigger'), fn.handleCloseSearch)

    if (props.$searchForm) {
      props.$searchForm.addEventListener('submit', fn.handleSearchSubmit)
      props.$searchForm.addEventListener('keyup', fn.handleSearchKeyUp)
    }
  },

  handleScroll: () => {
    props.stuck = window.pageYOffset > 1
    props.$header.classList.toggle('is-stuck', props.stuck)

    fn.handleBodyOffset()
  },

  handleResize: () => {
    props.headerHeight = props.$header.offsetHeight
  },

  handleBodyOffset: () => {
    if (props.needsBodyOffset && props.stuck) {
      document.body.style.paddingTop = `${props.headerHeight}px`
    } else {
      document.body.style.paddingTop = 0
    }
  },

  handleOpenSearch: () => {
    document.documentElement.classList.add(SELECTORS.htmlSearchActive)
    requestTimeout(() => {
      props.$searchForm.querySelector('input').focus()
    }, 250)
  },

  handleCloseSearch: () => {
    document.documentElement.classList.remove(SELECTORS.htmlSearchActive)
    props.$searchForm.querySelector('input').value = null
    props.$searchForm.classList.remove('has-error')
  },

  handleSearchSubmit: (e) => {
    const $input = e.target.querySelector('input')
    if ($input.value === '') {
      e.preventDefault()
      props.$searchForm.classList.add('has-error')
      $input.focus()
    }
  },

  handleSearchKeyUp: () => {
    props.$searchForm.classList.remove('has-error')
  }
}

export default {
  can: () => document.contains(props.$header),
  run: fn.init
}
