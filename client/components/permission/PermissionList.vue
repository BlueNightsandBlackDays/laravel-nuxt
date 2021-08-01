<template>
  <div class="col-12">
    <div class="d-flex">
      <h6 class="text-capitalize">
        Permission Lists
      </h6>
      <el-checkbox v-model="allow_all" class="ml-auto" name="allow-all" @change="allowAll">
        Allow All
      </el-checkbox>
    </div>
    <PuSkeleton v-if="loading" style="height: 100px" />

    <el-collapse v-else v-model="activeNames" class="row mt-3">
      <el-collapse-item v-for="(groupedPermission,key) in permissions" :key="key" :name="key" class="col-12 col-lg-6 col-xl-4">
        <template slot="title">
          <div class="d-flex w-100">
            <span class="text-capitalize tx-16 text-secondary">
              {{ key }}
            </span>
            <el-checkbox v-model="modules" size="mini" class="ml-auto mb-0 mr-3" :label="key" :name="key" @change="allow($event,key)">
              Allow
            </el-checkbox>
          </div>
        </template>

        <div class="pt-3">
          <div v-for="permission in groupedPermission" :key="permission.id" class="row">
            <div class="col-5">
              <p class="px-3 text-capitalize">
                {{ permission.name }}
              </p>
            </div>
            <div class="col-7">
              <el-checkbox v-model="selected_permissions" :name="key" :label="permission.id" @change="toggleAll">
                Allow
              </el-checkbox>
            </div>
          </div>
        </div>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  middleware: 'auth',
  props: {
    rolePermissions: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      activeNames: [],
      modules: [],
      allow_all: false,
      selected_permissions: []
    }
  },
  computed: mapGetters({
    permissions: 'permissions/groupedPermissions',
    all_permissions: 'permissions/permissions',
    loading: 'permissions/loading'
  }),
  watch: {
    selected_permissions (newVal, oldVal) {
      this.$emit('permissions', this.selected_permissions)
      this.toggleAll()
    },
    rolePermissions (newVal, oldVal) {
      this.selected_permissions = this.rolePermissions
    }
  },
  methods: {
    async getData () {
      await this.$store.dispatch('permissions/fetchPermissions', { limit: 100 })
      this.activeNames.push(Object.entries(this.permissions)[0][0])
    },
    toggleAll () {
      Object.entries(this.permissions).forEach((permission, index) => {
        const modules = permission[1].filter(p => this.selected_permissions.includes(p.id))
        if (modules.length === permission[1].length) {
          const index = this.modules.indexOf(permission[0])
          if (index === -1) {
            this.modules.push(permission[0])
          }
        } else {
          const index = this.modules.indexOf(permission[0])
          if (index > -1) {
            this.modules.splice(index, 1)
          }
        }
      })
      this.allow_all = this.selected_permissions.length === this.all_permissions.length
    },
    allow (event, key) {
      const filteredPermissions = Object.entries(this.permissions).filter(permission => permission[0] === key)

      if (event) {
        filteredPermissions.flat()[1].forEach((permission) => {
          const index = this.selected_permissions.indexOf(permission.id)
          if (index === -1) {
            this.selected_permissions.push(permission.id)
          }
        })
      } else {
        filteredPermissions.flat()[1].forEach((permission) => {
          const index = this.selected_permissions.indexOf(permission.id)
          if (index > -1) {
            this.selected_permissions.splice(index, 1)
          }
        })
      }
      this.toggleAll()
    },
    allowAll (event) {
      if (event) {
        this.all_permissions.forEach((permission) => {
          const index = this.selected_permissions.indexOf(permission.id)
          if (index === -1) {
            this.selected_permissions.push(permission.id)
          }
          Object.entries(this.permissions).forEach((permission) => {
            const index = this.modules.indexOf(permission[0])
            if (index === -1) {
              this.modules.push(permission[0])
            }
          })
        })
      } else {
        this.all_permissions.forEach((permission) => {
          const index = this.selected_permissions.indexOf(permission.id)
          if (index > -1) {
            this.selected_permissions.splice(index, 1)
          }
          Object.entries(this.permissions).forEach((permission) => {
            const index = this.modules.indexOf(permission[0])
            if (index > -1) {
              this.modules.splice(index, 1)
            }
          })
        })
      }
    },
    reset () {
      this.selected_permissions = []
      this.modules = []
      this.allow_all = false
    }
  }
}
</script>

<style scoped>

</style>
