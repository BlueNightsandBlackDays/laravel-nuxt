<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <el-page-header
          :title="$t('back')"
          :content="$t('create_users')"
          @back="goBack"
        />
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
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

          <!-- Password row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
            <div class="col-md-7">
              <!-- Password -->
              <el-form-item prop="password" class="m-1 p-0">
                <el-input
                  v-model="form.password"
                  type="password"
                  name="password"
                  clearable
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
                  v-model="form.password_confirmation"
                  type="password"
                  name="password_confirmation"
                  clearable
                  autocomplete="off"
                />
              </el-form-item>
            </div>
          </div>

          <!-- Role row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('roles') }}</label>
            <div class="col-md-7">
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
                    v-for="role in roles"
                    :key="role.id"
                    :label="role.name"
                    :value="role.id"
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
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="createUser('form')">
                {{ $t('create') }}
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
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',

  data () {
    const validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('password_is_required') + ''))
      } else if (value.length < 6) {
        callback(new Error(this.$t('6_characters') + ''))
      } else {
        if (this.form.password_confirmation !== '') {
          this.$refs.form.validateField('password_confirmation')
        }
        callback()
      }
    }
    const validatePasswordConfirm = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('enter_again') + ''))
      } else if (value !== this.form.password) {
        callback(new Error(this.$t('passwords_dont_match') + ''))
      } else {
        callback()
      }
    }
    return {
      form: new Form({
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        selected_roles: []
      }),
      rules: {
        first_name: [{ required: true, message: this.$t('first_name_is_required'), trigger: 'blur' }],
        middle_name: [{ required: true, message: this.$t('middle_name_is_required'), trigger: 'blur' }],
        last_name: [{ required: true, message: this.$t('last_name_is_required'), trigger: 'blur' }],
        email: [{ required: true, message: this.$t('email_is_required'), trigger: 'blur' },
          { type: 'email', message: this.$t('input_correct_email'), trigger: ['blur', 'change'] }],
        password: [{ required: true, validator: validatePassword, trigger: 'blur' }],
        password_confirmation: [{ required: true, validator: validatePasswordConfirm, trigger: 'blur' }],
        selected_roles: [{ required: true, message: this.$t('role_is_required'), trigger: 'change' }]
      }
    }
  },
  head () {
    return { title: this.$t('create') }
  },
  computed: mapGetters({
    roles: 'roles/roles',
    loading: 'roles/loading'
  }),
  methods: {
    goBack () {
      this.$router.push({ name: 'users-list' })
    },
    getRoles () {
      this.$store.dispatch('roles/fetchRoles', { limit: 100 })
    },
    createUser (formRule) {
      this.$refs[formRule].validate(async (valid) => {
        if (valid) {
          try {
            await axios.post('/users', this.form)
            this.$notify.success({
              title: this.$t('success') + '',
              message: this.$t('user') + ' ' + this.form.first_name + ' ' + this.form.middle_name + ' ' + this.$t('successfully_created')
            })
            // Redirect users.
            await this.$router.push({ name: 'users-list' })
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
