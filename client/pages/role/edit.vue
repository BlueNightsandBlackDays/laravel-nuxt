<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <el-page-header
          title=""
          :content="$t('update_roles')"
          @back="goBack"
        />
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <el-form ref="form" :model="form" :rules="rules" class="demo-form">
          <!-- Role row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-7">
              <!-- Role name -->
              <el-form-item prop="name" class="m-1 p-0">
                <el-input
                  v-model="form.name"
                  type="text"
                  name="name"
                />
              </el-form-item>
            </div>
          </div>

          <!-- Permission row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('permissions') }}</label>
            <div class="col-md-7">
              <!-- Permissions -->
              <el-tag
                v-for="selected_permission in permissions"
                :key="selected_permission.name"
                closable
                :disable-transitions="false"
                class="m-2"
                @close="removePermission(selected_permission)"
              >
                {{ selected_permission }}
              </el-tag>

              <!-- Select permissions -->
              <el-form-item prop="permission" class="m-1 p-0">
                <el-select
                  v-model="form.selected_permissions"
                  multiple
                  placeholder="Select Permissions"
                  @focus="getPermission"
                >
                  <el-option
                    v-for="permission in all_permissions.data"
                    :key="permission.id"
                    :label="permission.name"
                    :value="permission.id"
                  />
                </el-select>
              </el-form-item>
            </div>
          </div>

          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Reset Button -->
              <el-button class="el-button el-button--medium el-button--default" @click="resetForm('form')">
                {{ $t('reset') }}
              </el-button>
              <!-- Submit Button -->
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="updateRole('form')">
                {{ $t('update') }}
              </el-button>
            </div>
          </div>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  middleware: 'auth',
  data () {
    return {
      form: new Form({
        name: '',
        selected_permissions: []
      }),
      ruleForm: new Form({
        permission: ''
      }),
      loader: false,
      rules: {
        name: [{ required: true, message: 'Please enter a new role', trigger: 'change' }]
      }
    }
  },
  head () {
    return { title: this.$t('update') }
  },
  computed: mapGetters({
    role: 'roles/role',
    loading: 'roles/role_loading',
    all_permissions: 'permissions/permissions',
    permissions_loading: 'permissions/loading',
    permissions: 'permissions/permission',
    permission_loading: 'permissions/permission_loading'
  }),
  async mounted () {
    await this.getRole()
    await this.getAssignedPermission()
  },
  methods: {
    goBack () {
      this.$router.push({ name: 'roles-list' })
    },
    async getRole () {
      await this.$store.dispatch('roles/fetchRole', { id: this.$route.params.id })
      this.form.id = this.role.data.id
      this.form.name = this.role.data.name
    },
    async getAssignedPermission () {
      await this.$store.dispatch('permissions/fetchPermission', { id: this.$route.params.id })
    },
    getPermission () {
      this.$store.dispatch('permissions/fetchPermissions', { limit: 100 })
    },
    removePermission (indexPermission) {
      this.$confirm('Are you sure you want to revoke this permission?').then(async (_) => {
        try {
          const response = await axios.post(`/roles/revoke-permission/${this.form.id}/${indexPermission}`)
          const data = response.data
          if (data === 'revoked') {
            this.$notify.success({
              title: 'Success',
              message: 'Permission successfully revoked.'
            })
          } else {
            this.$notify.warning({
              title: 'Warning',
              message: 'You can\'t revoke permission.'
            })
          }
        } catch (e) {
          this.$notify.error({
            title: 'Error',
            message: e.message
          })
        }
      }).catch((_) => {})
    },
    updateRole (formRule) {
      this.$refs[formRule].validate(async (valid) => {
        if (valid) {
          try {
            // await this.$store.dispatch('roles/updateRole', this.form)
            const response = await axios.patch(`/roles/update/${this.form.id}`, this.form)
            const data = response.data
            if (data === 'Permission Exist') {
              this.$notify.warning({
                title: 'Warning',
                message: 'Permission already assigned to the role.'
              })
            } else {
              this.$notify.success({
                title: 'Success',
                message: 'Role successfully updated.'
              })
              // Redirect users.
              await this.$router.push({ name: 'roles-list' })
            }
          } catch (e) {
            this.$notify.error({
              title: 'Error',
              message: e.message
            })
          }
        } else {
          return false
        }
      })
    },
    resetForm (formRule) {
      this.$refs[formRule].resetFields()
    }
  }
}
</script>

<style scoped>

</style>
