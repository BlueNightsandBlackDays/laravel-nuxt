<template>
  <div class="container pd-x-0">
    <card>
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5">
            User Detail
          </h5>
        </div>
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="light" content="back to users" placement="top">
            <nuxt-link
              :to="{ name: 'users-list'}"
              class="nav-link el-icon-back"
            >
              Back
            </nuxt-link>
          </el-tooltip>
        </div>
      </div>
      <!-- Card-body -->
      <div class="card-body col-lg-12">
        User
      </div>
    </card>
  </div>
</template>

<script>
export default {
}
</script>

<style scoped>

</style>
