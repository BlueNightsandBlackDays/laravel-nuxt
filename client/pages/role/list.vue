<template>
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5 m-0">
            {{ $t('roles') }}
          </h5>
        </div>

        <!-- Create button -->
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="light" content="create new role" placement="top">
            <nuxt-link
              :to="{ name: 'roles-create'}"
              class="el-button el-button--mini el-button--primary m-0"
            >
              <i class="el-icon-plus" />
              {{ $t('create') }}
            </nuxt-link>
          </el-tooltip>
        </div>
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <data-tables-server
          :data="roles"
          :total="100"
          :loading="loading"
          :page-size="10"
          :pagination-props="{ background: false, pageSizes: [10] }"
          :filters="filters"
          layout="tool, table, pagination"
          @query-change="getRoles"
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

          <!-- #id -->
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
          </el-table-column>>

          <!-- Name -->
          <el-table-column
            prop="name"
            label="Name"
            sortable
            filter-hotelment="bottom-end"
          />

          <!-- Created at -->
          <el-table-column
            prop="created_at"
            label="Create At"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <i class="el-icon-time" />
              <span class="text-muted">{{ formatAttendanceDate (scope.row.created_at) }}</span>
            </template>
          </el-table-column>

          <!-- Updated at -->
          <el-table-column
            prop="updated_at"
            label="Last updated"
            sortable filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <i class="el-icon-time" />
              <span class="text-muted">{{ formatAttendanceDate (scope.row.updated_at) }}</span>
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
                    <el-tooltip class="item" effect="light" content="update role" placement="top">
                      <nuxt-link
                        class="el-link el-icon-edit el-link--primary"
                        :to="{ name: 'roles-update', params: { id: scope.row.id } }"
                      />
                    </el-tooltip>
                    <el-tooltip class="item" effect="light" content="delete role" placement="top">
                      <el-link
                        class="el-link el-icon-delete el-link--danger ml-3"
                        @click="handleDelete(scope.$index, scope.row)"
                      />
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
import { mapGetters } from 'vuex'
import moment from 'moment'

export default {
  middleware: 'auth',
  data () {
    return {
      filters: {
        search: ''
      },
      limit: 10,
      pageSize: 10
    }
  },
  head () {
    return { title: this.$t('users') }
  },
  computed: mapGetters({
    roles: 'roles/roles',
    meta: 'roles/meta',
    links: 'roles/links',
    loading: 'roles/loading',
    all_permissions: 'permissions/permissions',
    permissions_loading: 'permissions/loading'
  }),
  methods: {
    getRoles (query) {
      this.$store.dispatch('roles/fetchRoles', { limit: query.pageSize, page: query.page })
    },
    formatAttendanceDate (starTime) {
      if (starTime) {
        return moment(String(starTime)).format('ddd, MMM Do YYYY')
      }
    },
    handleDelete (index, row) {
      this.$confirm(`Are you sure you want to delete ${row.name} role?`).then(async (_) => {
        try {
          await this.$store.dispatch('roles/deleteRole', row.id)
          this.$notify.success({
            title: 'Success',
            message: 'Role successfully deleted.'
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
