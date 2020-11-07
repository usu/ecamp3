import { Model } from '@vuex-orm/core'

export default class ScheduleEntry extends Model {
  static entity = 'schedule-entries'

  static fields () {
    return {
      id: this.string(null),
      periodOffset: this.number(null)
    }
  }

  /**
   * get absolute startTime
   */
  get startTime () {
    return this.periodOffset + 100
  }
}
