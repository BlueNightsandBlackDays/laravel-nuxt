import axios from 'axios'
import Cookies from 'js-cookie'

// state
export const state = () => ({

})

// getters
export const getters = {

}

// mutations
export const mutations = {

}

// actions
export const actions = {
  async impersonateUser ({ commit, dispatch }, payload) {
    try {
      const { data } = await axios.get(`/impersonate/${payload.id}`)
      if (data) {
        const local = Cookies.get('auth._token.local')
        Cookies.set('backup_token', local)
        Cookies.set('auth._token.local', 'bearer ' + data.token, { expires: data.remember ? 365 : null })
        window.location = '/home'
      }
    } catch (e) {
    }
  }
}
