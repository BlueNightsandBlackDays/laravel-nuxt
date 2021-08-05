import axios from 'axios'
// state
export const state = () => ({
  attendances: [],
  links: {},
  meta: {},
  loading: false,
  attendance: [],
  attendance_loading: false
})

// getters
export const getters = {
  attendances: state => state.attendances,
  links: state => state.links,
  meta: state => state.meta,
  loading: state => state.loading,
  attendance: state => state.attendance,
  attendance_loading: state => state.attendance_loading
}

// mutations
export const mutations = {
  FETCH_ATTENDANCES_SUCCESS (state, attendances) {
    state.attendances = attendances.data
    state.links = attendances.links
    state.meta = attendances.meta
    state.loading = false
  },
  FETCH_ATTENDANCE_SUCCESS (state, payload) {
    state.attendance = payload.data
    state.attendance_loading = false
  },
  FETCH_ATTENDANCES_FAILURE (state) {
    state.loading = false
    state.attendances = []
    state.links = {}
    state.meta = {}
  },
  FETCH_ATTENDANCE_FAILURE (state) {
    state.loading = false
    state.attendance = []
  },
  SET_ATTENDANCES_LOADING (state, loading) {
    state.loading = loading
  },
  SET_ATTENDANCE_LOADING (state, payload) {
    state.attendance_loading = payload
  },
  SET_ID (state, payload) {
    state.id = payload
  }
}

// actions
export const actions = {
  async fetchAttendances ({ commit, dispatch }, params) {
    try {
      commit('SET_ATTENDANCES_LOADING', true)
      const data = await axios.get('/attendances', { params })
      commit('FETCH_ATTENDANCES_SUCCESS', data)
    } catch (e) {
      commit('FETCH_ATTENDANCES_FAILURE')
    }
  },
  async fetchAttendance ({ commit, dispatch }, payload) {
    try {
      commit('SET_ATTENDANCE_LOADING', true)
      const data = await axios.get(`/attendances/show/${payload.id}`)
      commit('FETCH_ATTENDANCE_SUCCESS', data)
    } catch (e) {
      commit('FETCH_ATTENDANCE_FAILURE')
    }
  }
}
