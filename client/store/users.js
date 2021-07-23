import axios from 'axios'
// state
export const state = () => ({
  users: [],
  links: {},
  meta: {},
  loading: false,
  user: {},
  user_loading: false,
  create_loading: false,
  update_loading: false,
  delete_loading: false
})

// getters
export const getters = {
  users: state => state.users,
  links: state => state.links,
  meta: state => state.meta,
  loading: state => state.loading,
  user: state => state.user,
  user_loading: state => state.user_loading,
  create_loading: state => state.create_loading,
  update_loading: state => state.update_loading,
  delete_loading: state => state.delete_loading
}

// mutations
export const mutations = {
  FETCH_USERS_SUCCESS (state, users) {
    state.users = users.data
    state.links = users.links
    state.meta = users.meta
    state.loading = false
  },
  FETCH_USER_SUCCESS (state, payload) {
    state.user = payload.data
    state.user_loading = false
  },
  FETCH_USERS_FAILURE (state) {
    state.loading = false
    state.users = []
    state.links = {}
    state.meta = {}
  },
  FETCH_USER_FAILURE (state) {
    state.user = {}
    state.loading = false
  },
  SET_USERS_LOADING (state, loading) {
    state.loading = loading
  },
  DELETE_USER_SUCCESS (state, payload) {
    state.users = state.users.filter(v => v.id !== payload.id)
    state.delete_loading = false
  },
  SET_USER_LOADING (state, payload) {
    state.user_loading = payload
  },
  SET_ID (state, payload) {
    state.id = payload
  },
  SET_CREATE_LOADING (state, payload) {
    state.create_loading = payload
  },
  SET_UPDATE_LOADING (state, payload) {
    state.update_loading = payload
  },
  SET_DELETE_LOADING (state, payload) {
    state.delete_loading = payload
  },
  RESET_USERS (state) {
    state.users = []
    state.meta = {}
    state.links = {}
  },
  RESET_USER (state) {
    state.user = {}
  }
}

// actions
export const actions = {
  async fetchUsers ({ commit, dispatch }, params) {
    try {
      commit('SET_USERS_LOADING', true)
      const { data } = await axios.get('/users', { params })
      commit('FETCH_USERS_SUCCESS', data)
    } catch (e) {
      commit('FETCH_USERS_FAILURE')
    }
  },
  async fetchUser ({ commit, dispatch }, payload) {
    try {
      commit('SET_USER_LOADING', true)
      commit('RESET_USER')
      const data = await axios.get(`/users/${payload.id}`)
      commit('FETCH_USER_SUCCESS', data)
    } catch (e) {
      commit('FETCH_USER_FAILURE')
    }
  },
  async createUser ({ commit, dispatch }, payload) {
    try {
      commit('SET_CREATE_LOADING', true)
      const { data } = await axios.post('/users', payload)
      commit('SET_CREATE_LOADING', false)
      dispatch('messages/setMessages', { type: 'success', title: 'User created', description: `User (${data.full_name + ' - ' + data.email}) successfully created.` }, { root: true })
    } catch (e) {
      commit('SET_CREATE_LOADING', false)
    }
  },
  async updateUser ({ commit, dispatch }, payload) {
    try {
      commit('SET_UPDATE_LOADING', true)
      const { id, ...formData } = payload
      const { data } = await axios.patch(`/users/${payload.id}`, formData)
      dispatch('messages/setMessages', { type: 'success', title: 'User updated', description: `User (${data.full_name + ' - ' + data.email}) successfully updated.` }, { root: true })

      commit('SET_UPDATE_LOADING', false)
    } catch (e) {
      commit('SET_UPDATE_LOADING', false)
    }
  },
  async deleteUser ({ commit, dispatch }, payload) {
    try {
      commit('SET_DELETE_LOADING', true)
      const { data } = await axios.delete(`/users/${payload}`)
      dispatch('messages/setMessages', { type: 'success', title: 'User deleted', description: `User (${data.full_name + ' - ' + data.email}) successfully deleted.` }, { root: true })

      commit('DELETE_USER_SUCCESS', data)
      dispatch('fetchUsers', { limit: 10, page: 1 })
    } catch (e) {
      commit('SET_DELETE_LOADING', false)
    }
  }
}
