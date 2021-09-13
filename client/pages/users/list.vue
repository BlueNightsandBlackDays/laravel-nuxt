<template>
  <!-- User list container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5 m-0">
            {{ $t('users') }}
          </h5>
        </div>

        <!-- Create button-->
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="dark" :content="$t('create_users')" placement="top">
            <nuxt-link
              :to="{ name: 'users-create'}"
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
          ref="filterTable"
          :data="usersData"
          :total="100"
          :loading="loading"
          :page-size="10"
          :pagination-props="{ background: false, pageSizes: [10, 20, 30, 40, 50, 100] }"
          :filters="filters"
          layout="tool, table, pagination"
          @query-change="getUsers"
        >
          <!-- Search -->
          <div slot="tool" class="row my-2">
            <div class="col-12 col-xl-10" />
            <div class="col-12 col-xl-2 mb-2 mb-xl-0 pl-xl-0 float-right">
              <el-input
                v-model="search"
                name="search"
                class="float-right"
                clearable
                size="mini"
                :placeholder="$t('search')"
                autosize
                @input="searchUsers"
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

          <!-- Name -->
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

          <!-- Email -->
          <el-table-column
            prop="email"
            :label="$t('email')"
            sortable
            filter-hotelment="bottom-end"
          />

          <!-- Role -->
          <el-table-column
            prop="roles"
            :label="$t('role')"
            sortable
            filter-hotelment="bottom-end"
          >
            <template slot-scope="scope">
              <span
                v-for="role in scope.row.roles"
                :key="role.name"
                class="mg-b-25"
              >
                <PuSkeleton>
                  <el-tag size="small" class="ml-2">
                    {{ role.name }}
                  </el-tag>
                </PuSkeleton>
              </span>
            </template>
          </el-table-column>

          <!-- Action buttons -->
          <el-table-column
            :label="$t('action')"
            fixed="right"
            width="130"
          >
            <template slot-scope="scope">
              <div class="d-flex">
                <div class="d-flex align-self-center ">
                  <nav class="nav nav-icon-only flex-nowrap" style="margin-left: auto;">
                    <el-tooltip class="item" effect="dark" :content="$t('view_users')" placement="top">
                      <nuxt-link
                        class="el-link el-link--default"
                        :to="{ name: 'users-view', params: { id: scope.row.id } }"
                      >
                        <i class="el-icon-view tx-16 tx-bold" aria-hidden="true" />
                      </nuxt-link>
                    </el-tooltip>
                    <el-tooltip class="item" effect="dark" :content="$t('update_users')" placement="top">
                      <nuxt-link
                        class="el-link el-link--default ml-2"
                        :to="{ name: 'users-update', params: { id: scope.row.id } }"
                      >
                        <i class="el-icon-edit tx-16 tx-bold" aria-hidden="true" />
                      </nuxt-link>
                    </el-tooltip>
                    <el-tooltip class="item" effect="dark" :content="$t('delete_users')" placement="top">
                      <el-link
                        :underline="false"
                        class="el-link el-link--default ml-2"
                        @click="handleDelete(scope.$index, scope.row)"
                      >
                        <i class="el-icon-delete tx-16 tx-bold" aria-hidden="true" />
                      </el-link>
                    </el-tooltip>
                  </nav>
                </div>
              </div>
            </template>
          </el-table-column>
        </data-tables-server>
        <div>
          {{  }}
        </div>
        <el-divider />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  middleware: 'auth',
  data () {
    return {
      filters: {
      },
      search: '',
      usersData: [],
      limit: 10,
      pageSize: 10
    }
  },
  head () {
    return { title: this.$t('users') }
  },
  computed: {
    users () { return this.$store.state.users.users },
    meta () { return this.$store.state.users.meta },
    links () { return this.$store.state.users.links },
    loading () { return this.$store.state.users.loading }
  },
  methods: {
    async getUsers (query) {
      await this.$store.dispatch('users/fetchUsers', { include: 'roles', limit: query.pageSize, page: query.page })
      this.usersData = this.users
    },
    async searchUsers () {
      const response = await axios.post('users/search', {
        search: {
          value: this.search
        }
      })
      const { data } = response.data
      this.usersData = data
    },
    handleDelete (index, row) {
      this.$confirm(this.$t('are_you_sure_you_want_delete') + ` ${row.first_name} ${row.middle_name}?`).then(async (_) => {
        try {
          const response = await axios.delete(`/users/${row.id}`)
          const data = response.data
          if (data === 'Cant_delete') {
            this.$notify.warning({
              title: this.$t('warning') + '',
              message: this.$t('you_cant_delete_your_self') + ''
            })
          } else {
            this.$notify.success({
              title: this.$t('success') + '',
              message: this.$t('user_successfully_deleted') + ''
            })
          }
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
