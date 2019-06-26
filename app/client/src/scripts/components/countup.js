import requestTimeout from '../common/requestTimeout'
import throttle from 'lodash/throttle'
import verge from 'verge'
const countUpModule = require('countup.js')

const SELECTORS = Selectors({
  counter: '[data-counter]'
})

const props = {
  $counters: document.querySelectorAll(SELECTORS.counter),
  counters: []
}

const fn = {
  init: () => {
    props.$counters.forEach(($el) => {
      const useGrouping = $el.dataset.useGrouping
      const end = $el.dataset.end
      const suffix = $el.dataset.suffix
      const prefix = $el.dataset.prefix

      const opts = {
        useGrouping: !!useGrouping,
        suffix,
        prefix
      }

      const counter = new countUpModule.CountUp($el, end, opts)
      counter._$el = $el

      props.counters.push(counter)
    })

    fn.bindEvents()

    requestTimeout(fn.update, 300)
  },

  bindEvents: () => {
    window.addEventListener('scroll', throttle(fn.update, 250))
  },

  update: () => {
    props.counters.forEach((counter, index) => {
      if (verge.inY(counter._$el)) {
        counter.start()
        delete props.counters[index]
      }
    })
  }
}

export default {
  can: () => props.$counters.length,
  run: fn.init
}
