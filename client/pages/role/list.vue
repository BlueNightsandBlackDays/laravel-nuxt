<template>
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5 mt-2">
            {{ $t('roles') }}
          </h5>
        </div>
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="light" content="create new role" placement="top">
            <nuxt-link
              :to="{ name: 'roles-create'}"
              class="el-button el-icon-plus el-button--small el-button--primary"
            >
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
          :pagination-props="{ background: true, pageSizes: [10] }"
          :filters="filters"
          layout="tool, table, pagination"
          @query-change="getRoles"
        >
          <div slot="tool" class="row my-2">
            <div class="col-12 col-xl-10" />
            <div class="col-12 col-xl-2 mb-2 mb-xl-0 pl-xl-0 float-right">
              <el-input class="float-right" clearable size="mini" hotelholder="Search" autosize>
                <i slot="prefix" class="el-input__icon el-icon-search" />
              </el-input>
            </div>
          </div>

          <!-- #id -->
          <el-table-column
            prop="name"
            label="Name"
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

          <el-table-column
            prop="created_at"
            label="Create At"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <span class="text-muted">{{ scope.row.created_at }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="updated_at"
            label="Last updated"
            sortable filter-hotelment="bottom-end">
            <template slot-scope="scope">
              <span class="text-muted">{{ scope.row.updated_at }}</span>
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
                    <el-tooltip class="item" effect="light" content="update role" placement="top">
                      <nuxt-link
                        class="el-button el-icon-edit el-button--small el-button--primary"
                        :to="{ name: 'roles-update', params: { id: scope.row.id } }"
                      />
                    </el-tooltip>
                    <el-tooltip class="item" effect="light" content="delete role" placement="top">
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
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',
  data () {
    return {
      limit: 10,
      filters: {
        search: ''
      },
      pageSize: 10
    }
  },
  computed: mapGetters({
    roles: 'roles/roles',
    meta: 'roles/meta',
    links: 'roles/links',
    loading: 'roles/loading'
  }),
  methods: {
    getRoles (query) {
      this.$store.dispatch('roles/fetchRoles', { limit: query.pageSize, page: query.page })
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
