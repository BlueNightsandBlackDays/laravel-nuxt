<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <el-page-header
          :title="$t('back')"
          :content="$t('user_detail')"
          @back="goBack"
        />
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <el-tabs v-model="activeName" type="card">
          <!-- User detail -->
          <el-tab-pane
            :label="$t('user_profile')"
            name="user"
          >
            <div class="col-sm-8 col-md-7 col-lg mg-t-20 mg-sm-t-0 mg-lg-t-25">
              <!-- Users full name and role -->
              <h5 class="mg-b-2 tx-spacing--1">
                <PuSkeleton> {{ user.first_name + ' ' + user.middle_name + ' ' + user.last_name }} </PuSkeleton>
                <span
                  v-for="role in roles.data"
                  :key="role.name"
                  class="mg-b-25"
                >
                  <PuSkeleton>
                    <el-tag size="small" class="ml-2">
                      {{ role.name }}
                    </el-tag>
                  </PuSkeleton>
                </span>
              </h5>

              <!-- Users email -->
              <p class="tx-color-03 mg-b-25">
                <span class="text-muted">
                  <PuSkeleton>{{ user.email }}</PuSkeleton>
                </span>
              </p>

              <!-- Edit button -->
              <div class="d-flex mg-b-25">
                <el-button
                  class="el-button el-icon-edit el-button--small el-button--primary"
                  @click="handleEdit()"
                >
                  {{ $t('edit') }}
                </el-button>
              </div>
            </div>

            <!-- Attendance chart -->
            <el-divider />
            <GChart
              :settings="{packages: ['calendar']}"
              type="Calendar"
              @ready="onChartReady"
            />
            <el-divider />
          </el-tab-pane>

          <!-- Attendance list -->
          <el-tab-pane
            :label="$t('attendance')"
            name="list"
          >
            <data-tables-server
              :data="attendance.data"
              :total="100"
              :loading="attendance_loading"
              :page-size="10"
              :pagination-props="{ background: false, pageSizes: [10, 20, 30, 40, 50, 100] }"
              layout="tool, table, pagination"
              @query-change="getAttendance"
            >
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

              <!-- Date -->
              <el-table-column
                prop="created_at"
                :label="$t('date')"
                sortable
                filter-hotelment="bottom-end"
              >
                <template slot-scope="scope">
                  <i class="el-icon-time" />
                  <span class="text-muted"> {{ formatAttendanceDate (scope.row.created_at) }}</span>
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
                  <span class="text-muted"> {{ formatAttendanceTime (scope.row.time_start) }}</span>
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
                  <span class="text-muted"> {{ formatAttendanceTime (scope.row.time_end) }}</span>
                </template>
              </el-table-column>

              <!-- Total hours -->
              <el-table-column
                prop="total_hours"
                :label="$t('total_hours')"
                sortable
                filter-hotelment="bottom-end"
              >
                <template slot-scope="scope">
                  <i class="el-icon-time" />
                  <span class="text-muted"> {{ timeDifference (scope.row.time_start, scope.row.time_end) + ' ' + $t('hours') }}</span>
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
                        <el-tooltip class="item" effect="dark" :content="$t('delete_attendances')" placement="top">
                          <el-link
                            :underline="false"
                            class="el-link el-link--danger"
                            @click="deleteAttendance(scope.$index, scope.row)"
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
          </el-tab-pane>
        </el-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'
import axios from 'axios'
import { GChart } from 'vue-google-charts'

export default {
  components: {
    GChart
  },

  middleware: 'auth',

  data () {
    return {
      activeName: 'user',
      limit: 10,
      pageSize: 10
    }
  },
  head () {
    return { title: this.$t('detail') }
  },
  computed: mapGetters({
    user: 'users/user',
    loading: 'users/user_loading',
    roles: 'roles/role',
    role_loading: 'roles/role_loading',
    attendance: 'attendance/attendance',
    attendance_loading: 'attendance/attendance_loading',
    chart_attendance: 'attendance/chart_attendance',
    chart_attendance_loading: 'attendance/chart_attendance_loading'
  }),
  async mounted () {
    await this.getUser()
    await this.getRole()
    await this.getChartAttendance()
  },
  methods: {
    goBack () {
      this.$router.push({ name: 'users-list' })
    },
    async getUser () {
      await this.$store.dispatch('users/fetchUser', { id: this.$route.params.id })
    },
    async getRole () {
      await this.$store.dispatch('roles/fetchRole', { id: this.$route.params.id })
    },
    handleEdit () {
      this.$router.push({ name: 'users-update', params: { id: this.user.id } })
    },
    getAttendance () {
      this.$store.dispatch('attendance/fetchAttendance', { id: this.$route.params.id })
    },
    async getChartAttendance () {
      await this.$store.dispatch('attendance/fetchChartAttendance', { id: this.$route.params.id })
    },
    onChartReady (chart, google) {
      let attendanceChart
      const dataTable = new google.visualization.DataTable()
      dataTable.addColumn({ type: 'date', id: 'Date' })
      dataTable.addColumn({ type: 'number', id: 'Attendance' })
      for (attendanceChart of this.chart_attendance) {
        dataTable.addRows([
          [new Date(attendanceChart), 1]
        ])
      }
      const options = {
        title: this.$t('chart_attend'),
        height: 175,
        legend: 'none',
        noDataPattern: {
          backgroundColor: '#76a7fa',
          color: '#a0c3ff'
        }
      }
      chart.draw(dataTable, options)
    },
    deleteAttendance (index, row) {
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
    },
    formatAttendanceDate (starTime) {
      if (starTime) {
        return moment(String(starTime)).format('ddd, MMM Do YYYY')
      }
    },
    formatAttendanceTime (starTime) {
      if (starTime) {
        return moment(String(starTime)).format('h:mm:ss a')
      }
    },
    timeDifference (startTime, endTime) {
      const starT = moment(startTime)
      const endT = moment(endTime)
      return endT.diff(starT, 'seconds')
    },
    toUpperCaseChanger (roleString) {
      return roleString.toUpperCase()
    }
  }
}
</script>

<style scoped>

</style>
