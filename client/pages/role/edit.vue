<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <el-page-header
          :title="$t('back')"
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
                  clearable
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
                @close="removePermission(selected_permission.name)"
              >
                {{ selected_permission.name }}
              </el-tag>

              <!-- Select permissions -->
              <el-form-item prop="selected_permissions" class="m-1 p-0">
                <el-select
                  v-model="form.selected_permissions"
                  multiple
                  :placeholder="$t('select_permissions')"
                  @focus="getPermission"
                >
                  <el-option
                    v-for="permission in all_permissions"
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
        <el-divider />
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
      loader: false,
      rules: {
        name: [{ required: true, message: this.$t('please_enter_a_new_role'), trigger: 'change' }]
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
      await this.$store.dispatch('roles/showRole', { id: this.$route.params.id })
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
      this.$confirm(this.$t('are_you_sure_you_want_revoke_this_permission') + '').then(async (_) => {
        try {
          const response = await axios.post(`/permissions/revoke/${this.form.id}/${indexPermission}`)
          const data = response.data
          if (data === 'revoked') {
            this.$notify.success({
              title: this.$t('success') + '',
              message: this.$t('permission_successfully_revoked') + ''
            })
          } else {
            this.$notify.warning({
              title: this.$t('warning') + '',
              message: this.$t('you_cant_revoke_permission') + ''
            })
          }
        } catch (e) {
          this.$notify.error({
            title: this.$t('error') + '',
            message: e.message
          })
        }
      }).catch((_) => {})
    },
    updateRole (formRule) {
      this.$refs[formRule].validate(async (valid) => {
        if (valid) {
          try {
            const response = await axios.patch(`/roles/${this.form.id}`, this.form)
            const data = response.data
            if (data === 'Permission Exist') {
              this.$notify.warning({
                title: this.$t('warning') + '',
                message: this.$t('permission_already_assigned_to_the_role') + ''
              })
            } else {
              this.$notify.success({
                title: this.$t('success') + '',
                message: this.$t('role_successfully_updated') + ''
              })
              // Redirect users.
              await this.$router.push({ name: 'roles-list' })
            }
          } catch (e) {
            this.$notify.error({
              title: this.$t('error') + '',
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
