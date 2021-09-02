<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <span class="mg-b-5 font-weight-lighter m-0" style="font-size: 20px">
            {{ $t('your_resent_attendances') }}
          </span>
        </div>

        <!-- Attendance start-end button -->
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="dark" :content="$t('start_or_end')" placement="top">
            <el-button
              class="el-button el-button--mini el-button--default m-0"
              :class="{active:isStarted}"
              @click="switchWorkStatus()"
            >
              <i class="el-icon-timer" />
              <span>{{ isStarted ? $t('start_work') : $t('end_work') }}</span>
            </el-button>
          </el-tooltip>
        </div>
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <!-- Attendance list -->
        <data-tables-server
          :data="attendance.data"
          :total="100"
          :loading="attendance_loading"
          :page-size="10"
          :pagination-props="{ background: false, pageSizes: [10, 20, 30, 40, 50, 100] }"
          layout="tool, table, pagination"
          @query-change="getCurrentAttendance"
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
        </data-tables-server>
        <el-divider />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'
import axios from 'axios'

export default {
  middleware: 'auth',
  data () {
    return {
      isStarted: true,
      limit: 10,
      pageSize: 10
    }
  },
  head () {
    return { title: this.$t('detail') }
  },
  computed: mapGetters({
    user: 'auth/user',
    attendance: 'attendance/attendance',
    attendance_loading: 'attendance/attendance_loading'
  }),
  methods: {
    async switchWorkStatus () {
      let data
      try {
        const response = await axios.post('/attendances/update/')
        data = response.data
        if (data === 'already_ended') {
          this.$notify.warning({
            title: this.$t('warning') + '',
            message: this.$t('work_already_ended_for_the_day') + ''
          })
        } else if (data === 'early') {
          this.$notify.warning({
            title: this.$t('warning') + '',
            message: this.$t('too_early_to_start') + ''
          })
        } else if (data === 'late') {
          this.$notify.warning({
            title: this.$t('warning') + '',
            message: this.$t('too_late_to_start') + ''
          })
        } else if (data === 'Work time has started') {
          this.isStarted = false
          this.$notify.success({
            title: this.$t('success') + '',
            message: this.$t('work_time_has_started') + ''
          })
        } else if (data === 'early_end') {
          this.$notify.warning({
            title: this.$t('warning') + '',
            message: this.$t('to_early_to_end') + ''
          })
        } else {
          this.isStarted = true
          this.$notify.info({
            title: this.$t('info') + '',
            message: this.$t('work_time_has_stopped') + ' ' + data + ' ' + this.$t('stop_hours')
          })
        }
      } catch (e) {
        this.$notify.error({
          title: this.$t('error') + '',
          message: e.message
        })
      }
    },
    getCurrentAttendance () {
      this.$store.dispatch('attendance/fetchAttendance', { id: this.user.id })
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
      return endT.diff(starT, 'minutes')
    }
  }
}
</script>

<style scoped>

</style>
