import axios from 'axios'
// state
export const state = () => ({
  permissions: [],
  links: {},
  meta: {},
  loading: false
})

// getters
export const getters = {
  permissions: state => state.permissions,
  links: state => state.links,
  meta: state => state.meta,
  loading: state => state.loading
  // groupedPermissions: state => groupBy(state.permissions, 'name')
}

// mutations
export const mutations = {
  FETCH_PERMISSIONS_SUCCESS (state, payload) {
    state.permissions = payload.data
    state.links = payload.links
    state.meta = payload.meta
    state.loading = false
  },
  FETCH_PERMISSIONS_FAILURE (state) {
    state.loading = false
    state.permissions = []
    state.links = {}
    state.meta = {}
  },
  RESET_PERMISSIONS (state) {
    state.permissions = []
    state.links = {}
    state.meta = {}
  },
  SET_PERMISSIONS_LOADING (state, payload) {
    state.loading = payload
  }
}

// actions
export const actions = {
  async fetchPermissions ({ commit, dispatch }, payload) {
    try {
      commit('SET_PERMISSIONS_LOADING', true)
      commit('RESET_PERMISSIONS')
      const data = await axios.get('/permissions', { params: payload })
      commit('FETCH_PERMISSIONS_SUCCESS', data)
    } catch (e) {
      commit('FETCH_PERMISSIONS_FAILURE')
    }
  }
}
