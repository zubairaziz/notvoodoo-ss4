import requestTimeout from 'scripts/common/requestTimeout'
import throttle from 'lodash/throttle'
import { on } from 'delegated-events'
import Flickity from 'flickity'

const bodyScrollLock = require('body-scroll-lock')
const disableBodyScroll = bodyScrollLock.disableBodyScroll
const enableBodyScroll = bodyScrollLock.enableBodyScroll

const SELECTORS = Selectors({
  nav: 'site-nav',
  navMenu: 'site-nav__menu',
  navItems: 'site-nav-items',
  navNext: 'site-nav__next',
  navPrev: 'site-nav__prev',
  navHeader: 'site-nav__header',
  navHeaderTitle: 'site-nav__header-title',
  activeSubmenu: 'is-active',
  htmlMenuActive: 'js-menu-active',
  navOpen: 'js-site-nav-open',
  navClose: 'js-site-nav-close',
  siteHeader: 'site-header',
  programs: 'site-nav-programs'
})

const props = {
  $nav: document.querySelector(SELECTORS.asClass('nav')),
  $navOpen: document.querySelector(SELECTORS.asClass('navOpen')),
  $navClose: document.querySelector(SELECTORS.asClass('navClose')),
  $navMenu: document.querySelector(SELECTORS.asClass('navMenu')),
  $navNext: document.querySelectorAll(SELECTORS.asClass('navNext')),
  $navPrev: document.querySelectorAll(SELECTORS.asClass('navPrev')),
  $navHeader: document.querySelector(SELECTORS.asClass('navHeader')),
  $navHeaderTitle: document.querySelector(SELECTORS.asClass('navHeaderTitle')),
  $siteHeader: document.querySelector(SELECTORS.asClass('siteHeader')),
  $programs: document.querySelector(SELECTORS.asClass('programs')),
  menuStack: []
}

const fn = {
  init: () => {
    fn.bindEvents()

    if (props.$programs) {
      fn.setupProgramsSlider()
    }
  },

  bindEvents: () => {
    document.addEventListener('click', fn.closeMenu)
    props.$navOpen.addEventListener('click', fn.openMenu)
    props.$navClose.addEventListener('click', fn.closeMenu)
    window.addEventListener('resize', throttle(fn.handleResize, 250))

    on('click', SELECTORS.asClass('navNext'), fn.moveMenuNext)
    on('click', SELECTORS.asClass('navPrev'), fn.moveMenuPrev)
  },

  setupProgramsSlider: () => {
    const slider = new Flickity(props.$programs.querySelector('.site-nav-programs__slider'), {
      cellAlign: 'left',
      pageDots: false,
      wrapAround: true,
      setGallerySize: false,
      groupCells: 2
    })

    slider.resize()
    slider.reposition()
  },

  openMenu: (e) => {
    e.stopPropagation()
    props.$nav.style.top = `${window.pageYOffset}px`
    document.documentElement.classList.add(SELECTORS.htmlMenuActive)

    if (props.$siteHeader.classList.contains('is-stuck')) {
      props.$siteHeader.style.top = `${props.$siteHeader.getBoundingClientRect().top * -1}px`
      props.$siteHeader.style.position = 'absolute'
    }

    props.$nav.style.height = `${window.innerHeight}px`

    disableBodyScroll(props.$nav)
  },

  closeMenu: ({ target }) => {
    if (!target.closest('.site-search-close') && (target.closest(SELECTORS.asClass('navClose')) || !target.closest(SELECTORS.asClass('nav')))) {
      document.documentElement.classList.remove(SELECTORS.htmlMenuActive)

      requestTimeout(() => {
        props.$nav.style.top = null
        props.$nav.style.height = null
        props.$siteHeader.style.position = null
        props.$siteHeader.style.top = null
      }, 300)

      fn.resetMenu()

      enableBodyScroll(props.$nav)
    }
  },

  moveMenuNext: ({ target }) => {
    const $next = target.closest('li').querySelector(SELECTORS.asClass('navItems'))
    props.menuStack.push($next)
    $next.classList.add(SELECTORS.activeSubmenu)
    fn.updateMenu()
  },

  moveMenuPrev: () => {
    const $prev = props.menuStack.pop()
    $prev.classList.remove(SELECTORS.activeSubmenu)
    fn.updateMenu()
  },

  resetMenu: () => {
    const currentMenuStack = props.menuStack

    props.menuStack = []
    props.$activeSubmenu = null

    requestTimeout(() => {
      currentMenuStack.forEach(el => el.classList.remove(SELECTORS.activeSubmenu))
      fn.updateMenu()
    }, 300)
  },

  updateMenu: () => {
    const menuDepth = props.menuStack.length
    props.$navMenu.style.transform = `translate3d(${menuDepth * -100}%, 0, 0)`

    fn.updateNavHeader()
  },

  updateNavHeader: () => {
    const $currentMenu = props.menuStack[props.menuStack.length - 1]
    let headerTitle = null

    if ($currentMenu) {
      const $parentLink = $currentMenu.closest('li').querySelector('a:first-of-type')
      headerTitle = $parentLink.getAttribute('title')
    }

    props.$navHeaderTitle.textContent = headerTitle
    props.$navHeader.classList.toggle('is-active', $currentMenu !== undefined)
  },

  handleResize: () => {
    if (window.innerWidth > 768) {
      fn.closeMenu({ target: document.body })
    }
  }
}

export default {
  can: () => document.contains(props.$nav),
  run: fn.init
}
