// moment.js
import moment from 'moment-timezone'
moment.tz.setDefault(process.env.timezone)

export default (context, inject) => {
  inject('moment', moment)
  context.$moment = moment
}
