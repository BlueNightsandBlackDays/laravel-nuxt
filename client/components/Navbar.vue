<template>
  <!-- Navigation bar -->
  <div class="bg-white">
    <el-menu class="el-menu-demo pt-1" mode="horizontal">
      <div class="d-flex justify-content-end mr-5 p-0">
        <!-- Language dropdown menu -->
        <el-submenu index="1">
          <template slot="title">
            {{ locales[locale] }}
          </template>
          <el-menu-item
            v-for="(value, key) in locales"
            :key="key"
            index="1-1"
          >
            <a href="#" class="text-dark dropdown-item p-0 m-0 pl-1" @click.prevent="setLocale(key)">
              {{ value }}
            </a>
          </el-menu-item>
        </el-submenu>

        <!-- Guest -->
        <template v-if="!user">
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
      </div>
    </el-menu>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { loadMessages } from '~/plugins/i18n'

export default {
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
    }
  }
}
</script>

<style scoped>

</style>
