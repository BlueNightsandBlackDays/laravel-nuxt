<template>
  <div>
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'attendances-view' }">
          {{ $t('attendance') }}
        </router-link>
      </template>
      <template v-else>
        <router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
        <router-link :to="{ name: 'register' }">
          {{ $t('register') }}
        </router-link>
      </template>
    </div>

    <div class="text-center">
      <div class="title mb-4">
        <span class="attendance">Attendance</span><span class="system">System</span>
        <!-- {{ title }} -->
      </div>

      <div class="links">
        <a href="https://github.com/bluenightsandblackdays/laravel-nuxt">github.com/bluenightsandblaackdays/laravel-nuxt</a>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'simple',

  data: () => ({
    title: process.env.appName
  }),

  head () {
    return { title: this.$t('home') }
  },

  computed: mapGetters({
    authenticated: 'auth/check'
  })
}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}

.attendance {
  color: #2e495e;
}

.system {
  color: #00c48d;
}
</style>
