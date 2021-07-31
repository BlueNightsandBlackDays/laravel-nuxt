import axios from 'axios'
export const state = () => ({
  roles: [],
  links: {},
  meta: {},
  loading: false,
  role: {},
  role_loading: false,
  delete_loading: false,
  create_loading: false,
  update_loading: false
})

export const getters = {
  roles: state => state.roles,
  links: state => state.links,
  meta: state => state.meta,
  loading: state => state.loading,
  role: state => state.role,
  role_loading: state => state.role_loading,
  delete_loading: state => state.delete_loading,
  create_loading: state => state.create_loading,
  update_loading: state => state.update_loading
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
  DELETE_ROLE_SUCCESS (state, role) {
    state.roles = state.roles.filter(v => v.id !== role.id)
    state.delete_loading = false
  },
  SET_ROLE_LOADING (state, payload) {
    state.role_loading = payload
  },
  SET_DELETE_LOADING (state, payload) {
    state.delete_loading = payload
  },
  SET_CREATE_LOADING (state, payload) {
    state.create_loading = payload
  },
  SET_UPDATE_LOADING (state, payload) {
    state.update_loading = payload
  },
  RESET_ROLES (state) {
    state.roles = []
    state.links = {}
    state.meta = {}
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
  async fetchRole ({ commit, dispatch }, payload) {
    try {
      commit('SET_ROLE_LOADING', true)
      commit('RESET_ROLE')
      const data = await axios.get(`/roles/${payload.id}`)
      commit('FETCH_ROLE_SUCCESS', data)
    } catch (e) {
      commit('FETCH_ROLE_FAILURE')
    }
  },
  async createRole ({ commit, dispatch }, payload) {
    try {
      commit('SET_CREATE_LOADING', true)
      const { data } = await axios.post('/roles', payload)
      commit('SET_CREATE_LOADING', false)
      dispatch('messages/setMessages', { type: 'success', title: 'Role created', description: `Role (${data.name}) successfully created.` }, { root: true })
    } catch (e) {
      commit('SET_CREATE_LOADING', false)
    }
  },
  async updateRole ({ commit, dispatch }, payload) {
    try {
      commit('SET_UPDATE_LOADING', true)
      const { id, ...formData } = payload
      const { data } = await axios.patch(`/roles/${payload.id}`, formData)
      commit('SET_UPDATE_LOADING', false)
      dispatch('messages/setMessages', { type: 'success', title: 'Role updated', description: `Role (${data.name}) successfully updated.` }, { root: true })
    } catch (e) {
      commit('SET_UPDATE_LOADING', false)
    }
  },
  async deleteRole ({ commit, dispatch }, payload) {
    try {
      commit('SET_DELETE_LOADING', true)
      const { data } = await axios.delete(`/roles/${payload}`)
      dispatch('messages/setMessages', { type: 'success', title: 'Role deleted', description: `Role (${data.name}) successfully deleted.` }, { root: true })
      commit('DELETE_ROLE_SUCCESS', data)
      dispatch('fetchRoles', { limit: 10, page: 1 })
    } catch (e) {
      commit('SET_DELETE_LOADING', false)
    }
  }
}
