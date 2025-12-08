// resources/js/plugins/dayjs.js
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
import relativeTime from 'dayjs/plugin/relativeTime'

// Extend dayjs with plugins
dayjs.extend(utc)
dayjs.extend(timezone)
dayjs.extend(relativeTime)

// Set default timezone (optional, e.g., Africa/Addis_Ababa)
dayjs.tz.setDefault('Africa/Addis_Ababa')

export default dayjs
