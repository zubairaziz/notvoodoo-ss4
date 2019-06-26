import AOS from 'aos'
import 'aos/src/sass/aos'

const fn = {
  init: () => {
    AOS.init({
      once: true
    })
  }
}

export default {
  can: () => true,
  run: fn.init
}
