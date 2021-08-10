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
          <span
            v-for="role in roles"
            :key="role"
            class="mg-b-25"
          >
            <PuSkeleton>
              <el-tag size="small" class="m-2">
                {{ toUpperCaseChanger (role) }}
              </el-tag>
            </PuSkeleton>
          </span>
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

        <!-- Chart report -->
        <GChart
          :settings="{packages: ['calendar']}"
          type="Calendar"
          :data="chartData"
          :options="chartOptions"
        />

        <el-divider />
        <!-- Attendance list -->
        <data-tables-server
          :data="attendance.data"
          :total="100"
          :loading="attendance_loading"
          :page-size="10"
          :pagination-props="{ background: true, pageSizes: [10, 20, 30, 40, 50, 100] }"
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
      filters: ({
        search: ''
      }),
      limit: 10,
      pageSize: 10,
      chartData: [
        ['a', 'b'],
        [new Date(2012, 3, 13), 5],
        [new Date(2012, 3, 14), 6],
        [new Date(2012, 3, 15), 7],
        [new Date(2012, 3, 16), 8],
        [new Date(2012, 3, 17), 9]
      ],
      chartOptions: {
        chart: {
          title: 'User Attendance',
          height: 350
        }
      }
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
    attendance_loading: 'attendance/attendance_loading'
  }),
  async mounted () {
    await this.getUser()
    await this.getRole()
  },
  methods: {
    async getUser () {
      await this.$store.dispatch('users/fetchUser', { id: this.$route.params.id })
    },
    async getRole () {
      await this.$store.dispatch('roles/showRole', { id: this.$route.params.id })
    },
    handleEdit () {
      this.$router.push({ name: 'users-update', params: { id: this.user.id } })
    },
    getAttendance () {
      this.$store.dispatch('attendance/fetchAttendance', { id: this.$route.params.id })
    },
    deleteAttendance (index, row) {
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
