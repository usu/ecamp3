import Vue from 'vue'
import Vuex from 'vuex'
import api from './api'
import lang from './lang'

import axios from 'axios'
import VuexORM from '@vuex-orm/core'
import VuexORMAxios from '@vuex-orm/plugin-axios'
import ScheduleEntry from '@/models/ScheduleEntry'

Vue.use(Vuex)

VuexORM.use(VuexORMAxios, {
  axios,
  baseURL: window.environment.API_ROOT_URL
})

const database = new VuexORM.Database()
database.register(ScheduleEntry)

const store = new Vuex.Store({
  plugins: [VuexORM.install(database)],
  modules: {
    api,
    lang
  },
  strict: process.env.NODE_ENV !== 'production'
})
export default store
