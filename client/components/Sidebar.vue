<template>
  <el-aside class="aside aside-fixed" :class="{ 'minimize': isMinimized, 'maximize': isHovering && isMinimized }" style="background-color: #2cd0f6">
    <div class="aside-header">
      <nuxt-link :to="{ name: user ? 'attendances-view' : 'welcome' }" class="navbar-brand text-dark p-0">
        {{ appName }}
      </nuxt-link>

      <a href="" class="aside-menu-link" @click.prevent="toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12" /><line x1="3" y1="6" x2="21" y2="6" /><line x1="3" y1="18" x2="21" y2="18" /></svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
      </a>
    </div>

    <div class="aside-body ps overflow-auto" style="padding: 15px" @mouseover="isHovering = true" @mouseout="isHovering = false">
      <div class="aside-loggedin">
        <div class="d-flex align-items-center justify-content-start">
          Hello
        </div>
      </div>
    </div>
  </el-aside>
</template>

<script>
import Cookies from 'js-cookie'

export default {
  data () {
    return {
      isHovering: false,
      min: false,
      appName: process.env.appName
    }
  },

  computed: {
    user () { return this.$store.state.users.user },
    isMinimized () { return this.min ? Cookies.get('isMinimized') === undefined ? this.min : JSON.parse(Cookies.get('isMinimized')) : Cookies.get('isMinimized') === undefined ? this.min : JSON.parse(Cookies.get('isMinimized')) }
  },

  methods: {
    toggle () {
      if (window.innerWidth < 992) {
        const body = document.body
        body.classList.toggle('show-aside')
      }
      Cookies.set('isMinimized', !this.isMinimized)
      this.min = !this.min
    },
    show (id) {
      const el = document.getElementById(id)
      el.classList.toggle('show')
    }
  }
}
</script>

<style scoped>

</style>
