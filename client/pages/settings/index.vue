<template>
  <div class="row">
    <div class="col-md-3">
      <card :title="$t('settings')" class="settings-card">
        <ul class="nav flex-column nav-pills">
          <li class="nav-item">
            <nuxt-link :to="{ name: 'settings.profile' }" class="nav-link" active-class="active">
              <span class="el-icon-user" />
              {{ $t('profile') }}
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link :to="{ name: 'settings.password' }" class="nav-link" active-class="active">
              <span class="el-icon-lock" />
              {{ $t('password') }}
            </nuxt-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="col-md-9">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth'
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
