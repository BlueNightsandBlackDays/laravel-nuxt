import axios from 'axios'
function lastWord (str) {
  const words = str.split(' ')
  return words[words.length - 1]
}

function removeLastWord (str) {
  const lastIndex = str.lastIndexOf(' ')
  return str.substring(0, lastIndex)
}
function sortObject (obj, key) {
  const tempObj = [...obj]
  tempObj.sort((a, b) => (a[key] > b[key]) ? 1 : -1)
  return tempObj
}

const groupBy = (array, key) => {
  array = sortObject(array, key)
  return array.reduce((accumulator, value) => {
    (accumulator[lastWord(value[key])] = accumulator[lastWord(value[key])] || []).push(
      { ...value, name: removeLastWord(value.name) }
    )
    return accumulator
  }, {})
}
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
  loading: state => state.loading,
  groupedPermissions: state => groupBy(state.permissions, 'name')
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
