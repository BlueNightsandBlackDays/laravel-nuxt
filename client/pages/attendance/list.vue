<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5 mt-2">
            {{ $t('attendance_list') }}
          </h5>
        </div>
      </div>
      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <data-tables-server
          :data="attendances.data"
          :total="100"
          :loading="loading"
          :page-size="10"
          :pagination-props="{ background: true, pageSizes: [10, 20, 30, 40, 50, 100] }"
          :filters="filters"
          layout="tool, table, pagination"
          @query-change="getAttendances"
        >
          <!-- Search -->
          <div slot="tool" class="row my-2">
            <div class="col-12 col-xl-10" />
            <div class="col-12 col-xl-2 mb-2 mb-xl-0 pl-xl-0 float-right">
              <el-input
                v-model.lazy="filters.search"
                type="search"
                name="search"
                class="float-right" clearable size="mini"
                hotelholder="Search"
                autosize
              >
                <i slot="prefix" class="el-input__icon el-icon-search" />
              </el-input>
            </div>
          </div>

          <!-- id -->
          <el-table-column
            prop="id"
            :label="$t('id')"
            sortable
            filter-hotelment="bottom-end"
            width="100"
          >
            <template slot-scope="scope">
              <span class="text-muted"> #{{ scope.row.id }}</span>
            </template>
          </el-table-column>

          <!-- User id -->
          <el-table-column
            prop="user_id"
            :label="$t('user_id')"
            sortable
            filter-hotelment="bottom-end"
            width="150"
          >
            <template slot-scope="scope">
              <span class="text-muted"> #{{ scope.row.user_id }}</span>
            </template>
          </el-table-column>

          <!-- Start time -->
          <el-table-column
            prop="time_start"
            :label="$t('time_start')"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <i class="el-icon-time" />
              <span class="text-muted"> {{ formatAttendanceDate (scope.row.time_start) }}</span>
            </template>
          </el-table-column>

          <!-- End time -->
          <el-table-column
            prop="time_end"
            :label="$t('time_end')"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <i class="el-icon-time" />
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
                    <el-tooltip class="item" effect="light" content="view attendance" placement="top">
                      <nuxt-link
                        class="el-button el-icon-view el-button--small el-button--default"
                        :to="{ name: 'users-view', params: { id: scope.row.user_id } }"
                      />
                    </el-tooltip>
                    <el-tooltip class="item" effect="light" content="delete attendance" placement="top">
                      <a
                        href="#"
                        class="el-button el-icon-delete el-button--small el-button--danger"
                        @click="handleDelete(scope.$index, scope.row)"
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
import axios from 'axios'
import { mapGetters } from 'vuex'
import moment from 'moment'

export default {
  middleware: 'auth',

  data () {
    return {
      filters: ({
        search: ''
      }),
      limit: 10,
      pageSize: 10
    }
  },
  head () {
    return { title: this.$t('users') }
  },
  computed: mapGetters({
    attendances: 'attendance/attendances',
    meta: 'attendance/meta',
    links: 'attendance/links',
    loading: 'attendance/loading'
  }),
  methods: {
    getAttendances (query) {
      this.$store.dispatch('attendance/fetchAttendances', { limit: query.pageSize, page: query.page })
    },
    formatAttendanceDate (starTime) {
      if (starTime) {
        return moment(String(starTime)).format('ddd, MMM Do YYYY, h:mm:ss a')
      }
    },
    handleDelete (index, row) {
      this.$confirm('Are you sure you want to delete this attendance entry?').then(async (_) => {
        try {
          await axios.delete(`/attendances/${row.id}`)
          this.$notify.success({
            title: 'Success',
            message: 'Attendance successfully deleted.'
          })
        } catch (e) {
          this.$notify.error({
            title: 'Error',
            message: e.message
          })
        }
      }).catch((_) => {})
    }
  }
}
</script>

<style scoped>

</style>
