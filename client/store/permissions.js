import axios from 'axios'
// state
export const state = () => ({
  permissions: [],
  links: {},
  meta: {},
  loading: false,
  permission: {},
  permission_loading: false
})

// getters
export const getters = {
  permissions: state => state.permissions,
  links: state => state.links,
  meta: state => state.meta,
  loading: state => state.loading,
  permission: state => state.permission,
  permission_loading: state => state.permission_loading
}

// mutations
export const mutations = {
  FETCH_PERMISSIONS_SUCCESS (state, payload) {
    state.permissions = payload.data
    state.links = payload.links
    state.meta = payload.meta
    state.loading = false
  },
  FETCH_PERMISSION_SUCCESS (state, payload) {
    state.permission = payload.data
    state.permission_loading = false
  },
  FETCH_PERMISSIONS_FAILURE (state) {
    state.loading = false
    state.permissions = []
    state.links = {}
    state.meta = {}
  },
  FETCH_PERMISSION_FAILURE (state) {
    state.permission = {}
    state.permission_loading = false
  },
  RESET_PERMISSIONS (state) {
    state.permissions = []
    state.links = {}
    state.meta = {}
  },
  RESET_PERMISSION (state) {
    state.permission = {}
  },
  SET_PERMISSIONS_LOADING (state, payload) {
    state.loading = payload
  },
  SET_PERMISSION_LOADING (state, payload) {
    state.permission_loading = payload
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
  },
  async fetchPermission ({ commit, dispatch }, payload) {
    try {
      commit('SET_PERMISSION_LOADING', true)
      commit('RESET_PERMISSION')
      // const data = await axios.get(`/roles/${payload.id}/permissions`)
      const data = await axios.get(`/permissions/show/${payload.id}`)
      commit('FETCH_PERMISSION_SUCCESS', data)
    } catch (e) {
      commit('FETCH_PERMISSION_FAILURE')
    }
  }
}
