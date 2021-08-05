<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5 mt-2">
            {{ $t('your_resent_attendances') }}
          </h5>
        </div>
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="light" content="start or end work" placement="top">
            <button
              id="hide-seen"
              ref="btnToggle"
              class="el-button el-button--medium el-button--default"
              @click="switchWorkStatus()"
            >
              <span class="el-icon-stopwatch" />
              Start Work
            </button>
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
          :pagination-props="{ background: true, pageSizes: [10, 20, 30, 40, 50, 100] }"
          :filters="filters"
          layout="tool, table, pagination"
          @query-change="getCurrentAttendance"
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

          <!-- Date -->
          <el-table-column
            prop="created_at"
            :label="$t('date')"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
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
              <span class="text-muted"> {{ timeDifference (scope.row.time_start, scope.row.time_end) }}</span>
            </template>
          </el-table-column>
        </data-tables-server>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
import moment from 'moment'
import axios from 'axios'

export default {
  middleware: 'auth',
  data () {
    return {
      filters: new Form({
        search: ''
      })
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
        const response = await axios.post('/attendances/update-current/')
        data = response.data
        if (data === 'Work time has started') {
          this.$refs.btnToggle.innerHTML = '<span class="el-icon-stopwatch" /> End Work'
        } else {
          this.$refs.btnToggle.innerHTML = '<span class="el-icon-stopwatch" /> Start Work'
        }
        this.$notify.info({
          title: 'Info',
          message: data
        })
      } catch (e) {
        this.$notify.error({
          title: 'Error',
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
      return endT.diff(starT, 'seconds')
    }
  }
}
</script>

<style scoped>

</style>
