<template>
  <div class="container pd-x-0">
    <card>
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5">
            Users
          </h5>
        </div>
        <div class="d-none d-md-block">
          <nuxt-link
            :to="{ name: 'users-create'}"
            class="el-button el-icon-plus el-button--small el-button--primary"
          >
            Create
          </nuxt-link>
        </div>
      </div>
      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <data-tables-server
          :data="users"
          :total="100"
          :loading="loading"
          :page-size="10"
          :pagination-props="{ background: true, pageSizes: [10, 20, 30, 40, 50, 100] }"
          :filters="filters"
          layout="tool, table, pagination"
          @query-change="getTableData"
        >
          <div slot="tool" class="row my-2">
            <div class="col-12 col-xl-10" />
            <div class="col-12 col-xl-2 mb-2 mb-xl-0 pl-xl-0 float-right">
              <el-input class="float-right" clearable size="mini" hotelholder="Search" autosize>
                <i slot="prefix" class="el-input__icon el-icon-search" />
              </el-input>
            </div>
          </div>

          <!-- Name -->
          <el-table-column
            prop="full_name"
            label="Name"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <span class="text-muted"> {{ scope.row.full_name }}</span>
            </template>
          </el-table-column>
          <!-- Email -->
          <el-table-column
            prop="email"
            label="Email"
            sortable
            filter-hotelment="bottom-end"
          />
          <!-- Action buttons -->
          <el-table-column
            :label="$t('Action')"
            fixed="right"
            width="200"
          >
            <template slot-scope="scope">
              <div class="d-flex">
                <div class="d-flex align-self-center ">
                  <nav class="nav nav-icon-only flex-nowrap" style="margin-left: auto;">
                    <nuxt-link
                      class="el-button el-icon-view el-button--small el-button--default"
                      :to="{ name: 'users-view', params: { id: scope.row.id } }"
                    />
                    <nuxt-link
                      class="el-button el-icon-edit el-button--small el-button--primary"
                      :to="{ name: 'users-update', params: { id: scope.row.id } }"
                    />
                    <a
                      href="#"
                      class="el-button el-icon-plus el-button--small el-button--danger"
                      @click="handleDelete(scope.$index, scope.row)"
                    />
                  </nav>
                </div>
              </div>
            </template>
          </el-table-column>
        </data-tables-server>
      </div>
    </card>
  </div>
</template>

<script>
export default {
  data () {
    return {
      limit: 10,
      filters: {
        search: ''
      },
      pageSize: 10
    }
  },
  computed: {
    users () { return this.$store.state.users.users },
    meta () { return this.$store.state.users.meta },
    links () { return this.$store.state.users.links },
    loading () { return this.$store.state.users.loading }
  },
  methods: {
    async getTableData (query) {
      await this.$store.dispatch('users/fetchUsers', { limit: query.pageSize, page: query.page })
    },
    handleDelete (index, row) {
      this.$confirm(`Are you sure you want to delete ${row.full_name} user?`).then(async (_) => {
        try {
          await this.$store.dispatch('users/deleteUser', row.id)
          this.$notify.success({
            title: 'Success',
            message: 'User successfully deleted.'
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
