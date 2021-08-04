<template>
  <!-- Navigation bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <!-- App name -->
      <nuxt-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand">
        {{ appName }}
      </nuxt-link>

      <!-- Navigation button -->
      <el-button :aria-label="$t('toggle_navigation')" class="navbar-toggler" type="button"
                 data-toggle="collapse" data-target="#navbarToggler"
                 aria-controls="navbarToggler" aria-expanded="false"
      >
        <span class="navbar-toggler-icon" />
      </el-button>

      <!-- Language dropdown menu -->
      <div id="navbarToggler" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <locale-dropdown />
        </ul>
      </div>

      <!-- Menus -->
      <div>
        <ul class="navbar-nav ml-auto">
          <!-- Authenticated -->
          <template v-if="user">
            <li class="nav-item">
              <nuxt-link
                :to="{ name: 'users-list'}"
                class="nav-link"
              >
                {{ $t('users') }}
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                :to="{ name: 'roles-list'}"
                class="nav-link"
              >
                {{ $t('roles') }}
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                :to="{ name: 'attendances-list'}"
                class="nav-link"
              >
                {{ $t('attendance_list') }}
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link
                :to="{ name: 'attendances-view'}"
                class="nav-link"
              >
                {{ $t('attendance') }}
              </nuxt-link>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-dark"
                 href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
              >
                <img :src="user.photo_url" alt="" class="rounded-circle profile-photo mr-1">
                {{ user.first_name + ' ' + user.middle_name }}
              </a>
              <div class="dropdown-menu">
                <nuxt-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                  <fa icon="cog" fixed-width />
                  {{ $t('settings') }}
                </nuxt-link>

                <div class="dropdown-divider" />
                <a class="dropdown-item pl-3" href="#" @click.prevent="logout">
                  <fa icon="sign-out-alt" fixed-width />
                  {{ $t('logout') }}
                </a>
              </div>
            </li>
          </template>
          <!-- Guest -->
          <template v-else>
            <li class="nav-item">
              <nuxt-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                {{ $t('login') }}
              </nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link :to="{ name: 'register' }" class="nav-link" active-class="active">
                {{ $t('register') }}
              </nuxt-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from './LocaleDropdown'

export default {
  components: {
    LocaleDropdown
  },

  data: () => ({
    appName: process.env.appName
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      await this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
</style>
