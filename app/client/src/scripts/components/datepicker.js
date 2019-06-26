import Lightpick from 'lightpick'
import moment from 'moment'
import 'lightpick/css/lightpick.css'

const SELECTORS = Selectors({
  dateRangeField: 'date-range-field',
  datepickerField: 'datepicker',
  dateFrom: 'datepicker--from',
  dateTo: 'datepicker--to',
  dateFromDisplay: 'datepicker--from-display',
  dateToDisplay: 'datepicker--to-display'
})

const props = {
  $datepickers: document.querySelectorAll(SELECTORS.asClass('datepickerField')),
  $dateRanges: document.querySelectorAll(SELECTORS.asClass('dateRangeField'))
}

const fn = {
  init: () => {
    props.$dateRanges.forEach(($el) => {
      const $from = $el.querySelector(SELECTORS.asClass('dateFrom'))
      const $to = $el.querySelector(SELECTORS.asClass('dateTo'))
      const $fromDisplay = $el.querySelector(SELECTORS.asClass('dateFromDisplay'))
      const $toDisplay = $el.querySelector(SELECTORS.asClass('dateToDisplay'))

      const picker = new Lightpick({
        parentEl: SELECTORS.asClass('dateRangeField'),
        field: $from,
        secondField: $to,
        hoveringTooltip: false,
        footer: true,
        format: 'YYYY-MM-DD',
        onSelect: () => {
          fn.updateDateRangeDisplay(picker, $from, $fromDisplay, $to, $toDisplay)

          if (picker.getStartDate() && picker.getEndDate()) {
            $from.closest('form').submit()
          }
        }
      })

      fn.updateDateRangeDisplay(picker, $from, $fromDisplay, $to, $toDisplay)
    })
  },

  updateDateRangeDisplay: (picker, $from, $fromDisplay, $to, $toDisplay) => {
    if (picker.getStartDate()) {
      $fromDisplay.innerHTML = moment($from.value).format('MMM D, YYYY')
      $fromDisplay.closest('.date-range-field__display').classList.add('is-touched')
    } else {
      $fromDisplay.innerHTML = $fromDisplay.dataset.placeholder
      $fromDisplay.closest('.date-range-field__display').classList.remove('is-touched')
    }

    if (picker.getEndDate()) {
      $toDisplay.innerHTML = moment($to.value).format('MMM D, YYYY')
      $toDisplay.closest('.date-range-field__display').classList.add('is-touched')
    } else {
      $toDisplay.innerHTML = $toDisplay.dataset.placeholder
      $toDisplay.closest('.date-range-field__display').classList.remove('is-touched')
    }
  }
}

export default {
  can: () => props.$datepickers.length,
  run: fn.init
}
