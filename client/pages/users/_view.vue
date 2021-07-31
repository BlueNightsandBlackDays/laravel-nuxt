<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5">
            {{ $t('user_detail') }}
          </h5>
        </div>
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="light" content="back to users" placement="top">
            <nuxt-link
              :to="{ name: 'users-list'}"
              class="el-button el-button--text"
            >
              <span class="el-icon-back" />
              {{ $t('back') }}
            </nuxt-link>
          </el-tooltip>
        </div>
      </div>
      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <div class="col-sm-8 col-md-7 col-lg mg-t-20 mg-sm-t-0 mg-lg-t-25">
          <h5 class="mg-b-2 tx-spacing--1">
            <PuSkeleton> {{ user.first_name + ' ' + user.middle_name + ' ' + user.last_name }} </PuSkeleton>
          </h5>
          <p class="tx-color-03 mg-b-25">
            <span class="text-muted">
              <PuSkeleton>{{ user.email }}</PuSkeleton>
            </span>
          </p>
          <div class="d-flex mg-b-25">
            <el-button
              class="el-button el-icon-edit el-button--small el-button--primary"
              @click="handleEdit()"
            >
              {{ $t('edit') }}
            </el-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',
  data () {
    return {
    }
  },
  head () {
    return { title: this.$t('detail') }
  },
  computed: mapGetters({
    user: 'users/user',
    loading: 'users/user_loading'
  }),
  async mounted () {
    await this.getData()
  },
  methods: {
    async getData () {
      await this.$store.dispatch('users/fetchUser', { id: this.$route.params.id })
    },
    handleEdit () {
      this.$router.push({ name: 'users-update', params: { id: this.user.id } })
    }
  }
}
</script>

<style scoped>

</style>
