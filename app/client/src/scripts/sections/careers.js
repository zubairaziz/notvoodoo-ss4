import { on } from 'delegated-events'

const SELECTORS = Selectors({
  jobPostingDropdown: 'job-posting-dropdown',
  jobPostingSidebar: 'job-posting-sidebar-column'
})

const props = {
  $jobPostingDropdown: document.querySelector(SELECTORS.asClass('jobPostingDropdown')),
  $jobPostingSidebar: document.querySelector(SELECTORS.asClass('jobPostingSidebar'))
}

const fn = {
  init: () => {
    fn.bindEvents()
    fn.filterPostings()
  },

  bindEvents: () => {
    if (props.$jobPostingDropdown) {
      on('change', SELECTORS.asClass('jobPostingDropdown'), fn.handlePostingChange)
    }

    on('click', '.job-postings-filter', (e) => {
      document.querySelector('.job-postings-filter.is-active').classList.remove('is-active')
      e.target.classList.add('is-active')
      fn.filterPostings()
    })
  },

  handlePostingChange: (e) => {
    const endpoint = `${props.$jobPostingSidebar.dataset.updateSidebarEndpoint}/${e.target.value}`

    ajax.get(endpoint, {
      responseType: 'text'
    }).then(resp => {
        props.$jobPostingSidebar.innerHTML = resp.data
      })
  },

  filterPostings: () => {
    const $activeFilter = document.querySelector('.job-postings-filter.is-active')

    if ($activeFilter) {
      const type = $activeFilter.dataset.type

      Array.from(document.querySelectorAll(`.job-postings-item`))
        .forEach(el => el.style.display = 'none')

      if (type === 'all') {
        Array.from(document.querySelectorAll(`.job-postings-item`))
          .forEach(el => el.style.display = 'block')
      } else {
        Array.from(document.querySelectorAll(`.job-postings-item[data-type="${type}"]`))
          .forEach(el => el.style.display = 'block')
      }
    }
  }
}

export default {
  can: () => document.body.classList.contains('pagetype-careerspage'),
  run: fn.init
}
