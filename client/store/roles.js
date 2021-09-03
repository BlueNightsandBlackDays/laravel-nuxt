import axios from 'axios'
export const state = () => ({
  roles: [],
  links: {},
  meta: {},
  loading: false,
  role: {},
  role_loading: false
})

export const getters = {
  roles: state => state.roles,
  links: state => state.links,
  meta: state => state.meta,
  loading: state => state.loading,
  role: state => state.role,
  role_loading: state => state.role_loading
}

export const mutations = {
  FETCH_ROLES_SUCCESS (state, roles) {
    state.roles = roles.data
    state.links = roles.links
    state.meta = roles.meta
    state.loading = false
  },
  FETCH_ROLE_SUCCESS (state, payload) {
    state.role = payload.data
    state.role_loading = false
  },
  FETCH_ROLES_FAILURE (state) {
    state.loading = false
    state.roles = []
  },
  FETCH_ROLE_FAILURE (state) {
    state.role = {}
    state.loading = false
  },
  SET_ROLES_LOADING (state, loading) {
    state.loading = loading
  },
  SET_ROLE_LOADING (state, payload) {
    state.role_loading = payload
  },
  RESET_ROLE (state) {
    state.role = {}
  }
}

export const actions = {
  async fetchRoles ({ commit, dispatch }, params) {
    try {
      commit('SET_ROLES_LOADING', true)
      const { data } = await axios.get('/roles', { params })
      commit('FETCH_ROLES_SUCCESS', data)
    } catch (e) {
      commit('FETCH_ROLES_FAILURE')
    }
  },
  async showRole ({ commit, dispatch }, payload) {
    try {
      commit('SET_ROLE_LOADING', true)
      commit('RESET_ROLE')
      const data = await axios.get(`/roles/${payload.id}`)
      commit('FETCH_ROLE_SUCCESS', data)
    } catch (e) {
      commit('FETCH_ROLE_FAILURE')
    }
  },
  async fetchRole ({ commit, dispatch }, payload) {
    try {
      commit('SET_ROLE_LOADING', true)
      commit('RESET_ROLE')
      // const data = await axios.get(`/users/${payload.id}/roles`)
      const data = await axios.get(`/roles/show/${payload.id}`)
      commit('FETCH_ROLE_SUCCESS', data)
    } catch (e) {
      commit('FETCH_ROLE_FAILURE')
    }
  }
}
