<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5 mt-2">
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
        <!-- Attendance list -->
        <data-tables-server
          :data="attendance.data"
          :total="100"
          :loading="attendance_loading"
          :page-size="10"
          :pagination-props="{ background: true, pageSizes: [10, 20, 30, 40, 50, 100] }"
          :filters="filters"
          layout="tool, table, pagination"
          @query-change="getAttendance"
        >
          <!-- Search -->
          <div slot="tool" class="row my-2">
            <div class="col-12 col-xl-10" />
            <div class="col-12 col-xl-2 mb-2 mb-xl-0 pl-xl-0 float-right">
              <el-form :model="filters" class="demo-form">
                <el-input
                  v-model="filters.search"
                  type="search"
                  name="search"
                  class="float-right" clearable size="mini"
                  hotelholder="Search"
                  autosize
                >
                  <i slot="prefix" class="el-input__icon el-icon-search" />
                </el-input>
              </el-form>
            </div>
          </div>

          <!-- id -->
          <el-table-column
            prop="id"
            label="#ID"
            sortable
            filter-hotelment="bottom-end"
            width="100"
          >
            <template slot-scope="scope">
              <span class="text-muted"> #{{ scope.row.id }}</span>
            </template>
          </el-table-column>

          <!-- Start time -->
          <el-table-column
            prop="time_start"
            label="Start Time"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <span class="text-muted"> {{ formatAttendanceDate (scope.row.time_start) }}</span>
            </template>
          </el-table-column>

          <!-- End time -->
          <el-table-column
            prop="time_end"
            label="End Time"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <span class="text-muted"> {{ formatAttendanceDate (scope.row.time_end) }}</span>
            </template>
          </el-table-column>
          <!-- Action buttons -->
          <el-table-column
            :label="$t('action')"
            fixed="right"
            width="200"
          >
            <template slot-scope="scope">
              <div class="d-flex">
                <div class="d-flex align-self-center ">
                  <nav class="nav nav-icon-only flex-nowrap" style="margin-left: auto;">
                    <el-tooltip class="item" effect="light" content="delete attendance" placement="top">
                      <a
                        href="#"
                        class="el-button el-icon-delete el-button--small el-button--danger"
                        @click="deleteAttendance(scope.$index, scope.row)"
                      />
                    </el-tooltip>
                  </nav>
                </div>
              </div>
            </template>
          </el-table-column>
        </data-tables-server>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Form from 'vform'
import moment from 'moment'

export default {
  middleware: 'auth',
  data () {
    return {
      limit: 10,
      filters: new Form({
        search: ''
      }),
      pageSize: 10
    }
  },
  head () {
    return { title: this.$t('detail') }
  },
  computed: mapGetters({
    user: 'users/user',
    loading: 'users/user_loading',
    attendance: 'attendance/attendance',
    attendance_loading: 'attendance/attendance_loading'
  }),
  async mounted () {
    await this.getUser()
  },
  methods: {
    async getUser () {
      await this.$store.dispatch('users/fetchUser', { id: this.$route.params.id })
    },
    handleEdit () {
      this.$router.push({ name: 'users-update', params: { id: this.user.id } })
    },
    getAttendance () {
      this.$store.dispatch('attendance/fetchAttendance', { id: this.$route.params.id })
    },
    deleteAttendance () {
    },
    formatAttendanceDate (starTime) {
      if (starTime) {
        return moment(String(starTime)).format('ddd, MMM Do YYYY, h:mm:ss a')
      }
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 8rem;
  height: 8rem;
  margin: 1rem 0;
}
</style>
