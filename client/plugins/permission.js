import Vue from 'vue'

Vue.directive('can', {
  bind (el, binding, vnode) {
    if (!JSON.parse(localStorage.getItem('permissions'))
      .includes(binding.expression
        .replace(/'/g, '')
        .replace(/"/g, ''))) {
      el.style.display = 'none'
    }
  }
})
