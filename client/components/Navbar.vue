<template>
  <!-- Navigation bar -->
  <div class="bg-white">
    <el-menu
      class="el-menu-demo pl-lg-5 pl-md-3 pl-sm-2"
      mode="horizontal"
    >
      <!-- App name -->
      <el-menu-item index="1">
        <nuxt-link :to="{ name: user ? 'attendances-view' : 'welcome' }" class="navbar-brand text-dark p-0">
          {{ appName }}
        </nuxt-link>
      </el-menu-item>

      <el-menu-item index="2">
        <el-divider direction="vertical" />
      </el-menu-item>

      <!-- Menus -->
      <!-- Authenticated -->
      <template v-if="user">
        <el-menu-item index="3">
          <nuxt-link :to="{ name: 'users-list'}" class="nav-link p-0">
            {{ $t('users') }}
          </nuxt-link>
        </el-menu-item>

        <!-- Roles -->
        <el-menu-item index="4">
          <nuxt-link :to="{ name: 'roles-list'}" class="nav-link p-0">
            {{ $t('roles') }}
          </nuxt-link>
        </el-menu-item>

        <!-- Attendance list -->
        <el-menu-item index="5">
          <nuxt-link :to="{ name: 'attendances-list'}" class="nav-link p-0">
            {{ $t('attendance_list') }}
          </nuxt-link>
        </el-menu-item>

        <!-- Attendance view -->
        <el-menu-item index="6">
          <nuxt-link :to="{ name: 'attendances-view'}" class="nav-link p-0">
            {{ $t('attendance') }}
          </nuxt-link>
        </el-menu-item>

        <el-menu-item index="7" class="pl-lg-5 pl-md-3 pl-sm-2 ml-lg-5 ml-md-3 ml-sm-2">
          <span class="pl-lg-4 pl-md-3 pl-sm-2 pr-lg-4 pr-md-3 pr-sm-2" />
        </el-menu-item>

        <!-- Language dropdown menu -->
        <el-submenu index="8" class="pl-lg-5 pl-md-3 pl-sm-2 ml-lg-5 ml-md-3 ml-sm-2">
          <template slot="title">
            {{ locales[locale] }}
          </template>
          <el-menu-item
            v-for="(value, key) in locales"
            :key="key"
            index="8-1"
          >
            <a href="#" class="text-dark dropdown-item p-0 m-0 pl-1" @click.prevent="setLocale(key)">
              {{ value }}
            </a>
          </el-menu-item>
        </el-submenu>

        <!-- Username and profile pic -->
        <el-submenu index="9">
          <template slot="title">
            <img :src="user.photo_url" alt="" class="rounded-circle profile-photo mr-1">
            {{ user.first_name + ' ' + user.middle_name }}
          </template>

          <!-- Settings -->
          <el-menu-item index="9-1">
            <nuxt-link
              :to="{ name: 'settings.profile' }"
              class="dropdown-item pl-3"
            >
              <fa icon="cog" fixed-width />
              {{ $t('settings') }}
            </nuxt-link>
          </el-menu-item>

          <!-- Log out -->
          <el-menu-item index="9-2">
            <a class="dropdown-item pl-3"
               href="#" @click.prevent="logout"
            >
              <fa icon="sign-out-alt" fixed-width />
              {{ $t('logout') }}
            </a>
          </el-menu-item>
        </el-submenu>
      </template>

      <!-- Guest -->
      <template v-else>
        <!-- Log in -->
        <el-menu-item index="10">
          <nuxt-link :to="{ name: 'login' }" class="nav-link p-0">
            {{ $t('login') }}
          </nuxt-link>
        </el-menu-item>

        <!-- Register -->
        <el-menu-item index="11">
          <nuxt-link :to="{ name: 'register' }" class="nav-link p-0" active-class="active">
            {{ $t('register') }}
          </nuxt-link>
        </el-menu-item>
      </template>
    </el-menu>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
// import LocaleDropdown from './LocaleDropdown'
import { loadMessages } from '~/plugins/i18n'

export default {
/*  components: {
    LocaleDropdown
  }, */

  data: () => ({
    appName: process.env.appName
  }),

  computed: mapGetters({
    user: 'auth/user',
    locale: 'lang/locale',
    locales: 'lang/locales'
  }),

  methods: {
    setLocale (locale) {
      if (this.$i18n.locale !== locale) {
        loadMessages(locale)

        this.$store.dispatch('lang/setLocale', { locale })
      }
    },
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
