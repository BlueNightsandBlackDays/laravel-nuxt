<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <span class="mg-b-5 font-weight-lighter m-0" style="font-size: 20px">
            {{ $t('attendance_list') }}
          </span>
        </div>
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <data-tables-server
          :data="attendances.data"
          :total="100"
          :loading="loading"
          :page-size="10"
          :pagination-props="{ background: false, pageSizes: [10, 20, 30, 40, 50, 100] }"
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

          <!-- User name -->
          <el-table-column
            prop="name"
            :label="$t('name')"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <span class="text-muted"> {{ scope.row.first_name + ' ' + scope.row.middle_name }}</span>
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
            width="120"
          >
            <template slot-scope="scope">
              <div class="d-flex">
                <div class="d-flex align-self-center ">
                  <nav class="nav nav-icon-only flex-nowrap" style="margin-left: auto;">
                    <el-tooltip class="item" effect="dark" :content="$t('view_attendances')" placement="top">
                      <nuxt-link
                        class="el-link el-link--default"
                        :to="{ name: 'users-view', params: { id: scope.row.user_id } }"
                      >
                        <i class="el-icon-view tx-18 tx-bold" aria-hidden="true" />
                      </nuxt-link>
                    </el-tooltip>
                    <el-tooltip class="item" effect="dark" :content="$t('delete_attendances')" placement="top">
                      <el-link
                        :underline="false"
                        class="el-link el-link--danger ml-3"
                        @click="handleDelete(scope.$index, scope.row)"
                      >
                        <i class="el-icon-delete tx-18 tx-bold" aria-hidden="true" />
                      </el-link>
                    </el-tooltip>
                  </nav>
                </div>
              </div>
            </template>
          </el-table-column>
        </data-tables-server>
        <el-divider />
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
    return { title: this.$t('attendances') }
  },
  computed: mapGetters({
    attendances: 'attendance/attendances',
    meta: 'attendance/meta',
    links: 'attendance/links',
    loading: 'attendance/loading',
    user: 'users/user',
    user_loading: 'users/user_loading'
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
      this.$confirm(this.$t('are_you_sure_you_want_delete_attendance') + '').then(async (_) => {
        try {
          await axios.delete(`/attendances/${row.id}`)
          this.$notify.success({
            title: this.$t('success') + '',
            message: this.$t('attendance_successfully_deleted') + ''
          })
        } catch (e) {
          this.$notify.error({
            title: this.$t('error') + '',
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
