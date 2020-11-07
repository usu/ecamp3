import { Model } from '@vuex-orm/core'
import Period from '@/models/Period'

export default class ScheduleEntry extends Model {
  static entity = 'schedule-entries'

  static fields () {
    return {
      id: this.string(null),
      periodOffset: this.number(null),
      length: this.number(null),
      periodId: this.attr(null),
      period: this.belongsTo(Period, 'periodId')
    }
  }

  static apiConfig = {
    dataTransformer: ({ data, headers }) => {
      data._embedded.items.forEach(
        item => { item.periodId = item._embedded.period.id }
      )

      return data._embedded.items
    }
  }

  /**
   * get absolute startTime (as timestamp)
   */
  get startTime () {
    return this.period.startAsTimestamp + (this.periodOffset * 60000)
  }

  /**
   * get absolute endTime (as timestamp)
   */
  get endTime () {
    return this.startTime + (this.length * 60000)
  }
}
