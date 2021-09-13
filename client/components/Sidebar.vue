<!-- Authenticated -->
<template>
  <el-aside v-if="user" class="aside aside-fixed" :class="{ 'minimize': isMinimized, 'maximize': isHovering && isMinimized }">
    <!-- Sidebar header  -->
    <div class="aside-header">
      <!-- App name  -->
      <nuxt-link :to="{ name: user ? 'attendances-view' : 'welcome' }" class="aside-logo p-0">
        <span class="attendance">Attendance</span><span class="system">System</span>
      </nuxt-link>

      <!-- Toggle button  -->
      <a href="" class="aside-menu-link" @click.prevent="toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="feather feather-menu"
        >
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="feather feather-x"
        >
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </a>
    </div>

    <!-- Sidebar menu  -->
    <div class="aside-body ps overflow-auto"
         style="padding: 15px"
         @mouseover="isHovering = true"
         @mouseout="isHovering = false"
    >
      <div class="aside-loggedin">
        <!-- Profile pic -->
        <div class="d-flex align-items-center justify-content-start">
          <el-tooltip :content="$t('user_profile')">
            <div class="avatar">
              <img :src="user.photo_url" alt="User profile" class="rounded-circle w-100 h-100">
            </div>
          </el-tooltip>

          <div class="aside-alert-link">
            <el-tooltip class="item" effect="dark" :content="$t('settings')" placement="top">
              <nuxt-link :to="{ name: 'settings.profile' }" class="ml-2">
                <fa icon="cog" fixed-width />
              </nuxt-link>
            </el-tooltip>
            <el-tooltip class="item" effect="dark" :content="$t('logout')" placement="top">
              <a href="#" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width />
              </a>
            </el-tooltip>
          </div>
        </div>

        <!-- Username -->
        <div class="aside-loggedin-user">
          <a href="#loggedinMenu" class="d-flex align-items-center justify-content-between mg-b-2" data-toggle="collapse">
            <h6 class="tx-semibold mg-b-0">
              {{ user.first_name + ' ' + user.middle_name }}
            </h6>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-chevron-down"
            >
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </a>
          <div class="overflow-auto d-flex">
            <div class="tx-color-03 tx-12 mg-b-0">
              <span class="text-nowrap">{{ user.email }}</span>
            </div>
          </div>
        </div>

        <!-- Settings and logout dropdown -->
        <div id="loggedinMenu" class="collapse">
          <ul class="nav nav-aside mg-b-0">
            <!-- Settings -->
            <li class="nav-item">
              <nuxt-link :to="{ name: 'settings.profile' }" class="nav-link ml-1" active-class="active">
                <fa icon="cog" fixed-width style="width: 18px;" />
                <span class="ml-0"> {{ $t('settings') }} </span>
              </nuxt-link>
            </li>

            <!-- Log out -->
            <li class="nav-item">
              <a href="#" class="nav-link ml-1" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width style="width: 18px;" />
                <span class="ml-0"> {{ $t('logout') }} </span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Menus -->
        <ul class="nav nav-aside">
          <li class="mg-t-20">
            <nuxt-link :to="{ name: 'home'}" class="nav-link">
              <span class="nav-label tx-uppercase">Home</span>
            </nuxt-link>
          </li>

          <!-- Access management label -->
          <li v-can="'view any user'" class="mg-t-1">
            <span class="nav-label tx-uppercase">Access management</span>
          </li>

          <!-- Users -->
          <li v-can="'view any user'" class="nav-item">
            <nuxt-link :to="{ name: 'users-list'}" class="nav-link ml-1">
              <i class="el-icon-user tx-18 tx-bold" aria-hidden="true" />
              <span class="ml-3">{{ $t('users') }}</span>
            </nuxt-link>
          </li>

          <!-- Roles -->
          <li v-can="'view any role'" class="nav-item">
            <nuxt-link :to="{ name: 'roles-list'}" class="nav-link ml-1">
              <i class="el-icon-bangzhu tx-18 tx-bold" aria-hidden="true" />
              <span class="ml-3">{{ $t('roles') }}</span>
            </nuxt-link>
          </li>

          <!-- Attendance label -->
          <li class="mg-t-2">
            <span class="nav-label tx-uppercase">Attendances</span>
          </li>

          <!-- Attendance list -->
          <li v-can="'view any attendance'" class="nav-item">
            <nuxt-link :to="{ name: 'attendances-list'}" class="nav-link ml-1">
              <i class="el-icon-time tx-18 tx-bold" aria-hidden="true" />
              <span class="ml-3">{{ $t('attendance_list') }}</span>
            </nuxt-link>
          </li>

          <!-- Attendance view -->
          <li class="nav-item">
            <nuxt-link :to="{ name: 'attendances-view'}" class="nav-link ml-1">
              <i class="el-icon-timer tx-18 tx-bold" aria-hidden="true" />
              <span class="ml-3">{{ $t('attendance') }}</span>
            </nuxt-link>
          </li>
        </ul>

        <div class="ps__rail-x" style="left: 0; top: 0;">
          <div class="ps__thumb-x" tabindex="0" style="left: 0; width: 0;" />
        </div><div class="ps__rail-y" style="top: 0; right: 0;">
          <div class="ps__thumb-y" tabindex="0" style="top: 0; height: 0;" />
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
    user () { return this.$store.state.auth.user },
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
.attendance {
  color: #2e495e;
}

.system {
  color: #00c48d;
}
</style>
