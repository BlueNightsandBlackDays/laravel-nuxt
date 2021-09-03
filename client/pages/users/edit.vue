<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <!-- Update user -->
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <el-page-header
          :title="$t('back')"
          :content="$t('update_users')"
          @back="goBack"
        />
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <el-tabs v-model="activeName" type="card">
          <!-- Update user profile -->
          <el-tab-pane
            :label="$t('user_profile')"
            name="user"
          >
            <el-form ref="form" :model="form" :rules="rules" class="demo-form">
              <!-- First name row -->
              <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
                <div class="col-md-7">
                  <!-- First name -->
                  <el-form-item prop="first_name" class="m-1 p-0">
                    <el-input
                      v-model="form.first_name"
                      type="text"
                      name="name"
                      clearable
                    />
                  </el-form-item>
                </div>
              </div>

              <!-- Middle name row -->
              <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('middle_name') }}</label>
                <div class="col-md-7">
                  <!-- Middle name -->
                  <el-form-item prop="middle_name" class="m-1 p-0">
                    <el-input
                      v-model="form.middle_name"
                      type="text"
                      name="name"
                      clearable
                    />
                  </el-form-item>
                </div>
              </div>

              <!-- Last name row -->
              <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
                <div class="col-md-7">
                  <!-- Last name -->
                  <el-form-item prop="last_name" class="m-1 p-0">
                    <el-input
                      v-model="form.last_name"
                      type="text"
                      name="name"
                      clearable
                    />
                  </el-form-item>
                </div>
              </div>

              <!-- Email row -->
              <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                <div class="col-md-7">
                  <!-- Email -->
                  <el-form-item prop="email" class="m-1 p-0">
                    <el-input
                      v-model="form.email"
                      type="email"
                      name="email"
                      clearable
                      autocomplete="off"
                    />
                  </el-form-item>
                </div>
              </div>

              <!-- Roles row -->
              <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('roles') }}</label>
                <div class="col-md-7">
                  <!-- Roles -->
                  <el-tag
                    v-for="role in roles"
                    :key="role"
                    closable
                    :disable-transitions="false"
                    class="m-2"
                    @close="removeRole(role)"
                  >
                    {{ role }}
                  </el-tag>

                  <!-- Select role -->
                  <el-form-item prop="selected_roles" class="m-1 p-0">
                    <el-select
                      v-model="form.selected_roles"
                      multiple
                      :loading="loading"
                      size="large"
                      :placeholder="$t('select_roles')"
                      @focus="getRoles"
                    >
                      <el-option
                        v-for="role in all_roles"
                        :key="role.name"
                        :label="role.name"
                        :value="role.name"
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
                  <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="updateUser('form')">
                    {{ $t('update') }}
                  </el-button>
                </div>
              </div>
            </el-form>
          </el-tab-pane>

          <!-- Update password -->
          <el-tab-pane
            :label="$t('password')"
            name="password"
          >
            <el-form ref="passwordForm" :model="passwordForm" :rules="rules" class="demo-form">
              <!-- Password row -->
              <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
                <div class="col-md-7">
                  <!-- Password -->
                  <el-form-item prop="password" class="m-1 p-0">
                    <el-input
                      v-model="passwordForm.password"
                      type="password"
                      name="password"
                      autocomplete="off"
                    />
                  </el-form-item>
                </div>
              </div>

              <!-- Confirm password row -->
              <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
                <div class="col-md-7">
                  <!-- Confirm password -->
                  <el-form-item prop="password_confirmation" class="m-1 p-0">
                    <el-input
                      v-model="passwordForm.password_confirmation"
                      type="password"
                      name="password_confirmation"
                      autocomplete="off"
                    />
                  </el-form-item>
                </div>
              </div>

              <!-- Buttons row -->
              <div class="form-group row">
                <div class="col-md-7 offset-md-3 d-flex justify-content-end">
                  <!-- Reset Button -->
                  <el-button class="el-button el-button--medium el-button--default" @click="resetForm('passwordForm')">
                    {{ $t('reset') }}
                  </el-button>
                  <!-- Submit Button -->
                  <el-button :loading="passwordForm.busy" class="el-button el-button--medium el-button--primary" @click="updatePassword('passwordForm')">
                    {{ $t('change_password') }}
                  </el-button>
                </div>
              </div>
            </el-form>
          </el-tab-pane>
        </el-tabs>
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
    const validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('password_is_required') + ''))
      } else if (value.length < 6) {
        callback(new Error(this.$t('6_characters') + ''))
      } else {
        if (this.passwordForm.password_confirmation !== '') {
          this.$refs.passwordForm.validateField('password_confirmation')
        }
        callback()
      }
    }
    const validatePasswordConfirm = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('enter_again') + ''))
      } else if (value !== this.passwordForm.password) {
        callback(new Error(this.$t('passwords_dont_match') + ''))
      } else {
        callback()
      }
    }
    return {
      activeName: 'user',
      form: new Form({
        id: '',
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        selected_roles: []
      }),
      passwordForm: new Form({
        password: '',
        password_confirmation: ''
      }),
      rules: {
        first_name: [{ required: true, message: this.$t('first_name_is_required'), trigger: 'blur' }],
        middle_name: [{ required: true, message: this.$t('middle_name_is_required'), trigger: 'blur' }],
        last_name: [{ required: true, message: this.$t('last_name_is_required'), trigger: 'blur' }],
        email: [{ required: true, message: this.$t('email_is_required'), trigger: 'blur' },
          { type: 'email', message: this.$t('input_correct_email'), trigger: ['blur', 'change'] }],
        password: [{ required: true, validator: validatePassword, trigger: 'blur' }],
        password_confirmation: [{ required: true, validator: validatePasswordConfirm, trigger: 'blur' }]
      }
    }
  },
  head () {
    return { title: this.$t('update') }
  },
  computed: mapGetters({
    user: 'users/user',
    loading: 'users/user_loading',
    roles: 'roles/role',
    role_loading: 'roles/role_loading',
    all_roles: 'roles/roles',
    roles_loading: 'roles/loading'
  }),
  async mounted () {
    await this.getUser()
    await this.getRole()
  },
  methods: {
    goBack () {
      this.$router.push({ name: 'users-list' })
    },
    async getUser () {
      await this.$store.dispatch('users/fetchUser', { id: this.$route.params.id })
      this.form.id = this.user.id
      this.form.first_name = this.user.first_name
      this.form.middle_name = this.user.middle_name
      this.form.last_name = this.user.last_name
      this.form.email = this.user.email
    },
    async getRole () {
      await this.$store.dispatch('roles/fetchRole', { id: this.$route.params.id })
    },
    getRoles () {
      this.$store.dispatch('roles/fetchRoles', { limit: 100 })
    },
    removeRole (indexRole) {
      this.$confirm(this.$t('are_you_sure_you_want_to_revoke_this_role') + '?').then(async (_) => {
        try {
          // const response = await axios.delete(`/users/${this.form.id}/roles/${indexRole}`)
          const response = await axios.post(`/roles/revoke/${this.form.id}/${indexRole}`)
          const data = response.data
          if (data === 'revoked') {
            this.$notify.success({
              title: this.$t('success') + '',
              message: this.$t('role_successfully_revoked') + ''
            })
          } else {
            this.$notify.warning({
              title: this.$t('warning') + '',
              message: this.$t('you_cant_revoke_role') + ''
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
    updateUser (formRule) {
      this.$refs[formRule].validate(async (valid) => {
        if (valid) {
          try {
            const response = await axios.patch(`/users/${this.form.id}`, this.form)
            const data = response.data
            if (data === 'Role exist') {
              this.$notify.warning({
                title: this.$t('warning') + '',
                message: this.$t('role_already_assigned_to_the_user') + ''
              })
            } else {
              this.$notify.success({
                title: this.$t('success') + '',
                message: this.$t('user') + ' ' + this.form.first_name + ' ' + this.form.middle_name + ' ' + this.$t('successfully_updated')
              })

              // Redirect users.
              await this.$router.push({ name: 'users-list' })
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
    updatePassword (formRule) {
      this.$refs[formRule].validate(async (valid) => {
        if (valid) {
          try {
            const response = await axios.patch(`/users/password/${this.form.id}`, this.passwordForm)
            const data = response.data
            if (data === 'Password updated') {
              this.$notify.success({
                title: this.$t('success') + '',
                message: this.$t('user_password_successfully_changed') + ''
              })
              // Redirect users.
              await this.$router.push({ name: 'users-list' })
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
