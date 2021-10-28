window.moment = require('moment');
require('moment-timezone');

import Alpine from 'alpinejs'
import trap from '@alpinejs/trap'
Alpine.plugin(trap)
Alpine.start()
window.Alpine = Alpine
