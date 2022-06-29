import moment from 'moment'
window.moment = moment
import momentTimezone from 'moment-timezone'
window.moment = momentTimezone


import Alpine from 'alpinejs'
import trap from '@alpinejs/focus'
Alpine.plugin(trap)
Alpine.start()
window.Alpine = Alpine
