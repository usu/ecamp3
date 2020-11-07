import { Model } from '@vuex-orm/core'
import ScheduleEntry from '@/models/ScheduleEntry'

export default class Period extends Model {
  static entity = 'periods'

  static fields () {
    return {
      id: this.string(null),
      description: this.string(null),
      start: this.string(null),
      end: this.string(null),
      scheduleEntries: this.hasMany(ScheduleEntry, 'periodId')
    }
  }

  /**
   * convert start (string) to timestamp
   */
  get startAsTimestamp () {
    return Date.parse(this.start)
  }
}
