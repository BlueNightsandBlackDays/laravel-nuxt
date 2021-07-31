<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5">
            {{ $t('create_users') }}
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
        <el-form ref="form" :model="form" class="demo-form">
          <!-- First name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-7">
              <!-- First name -->
              <el-form-item prop="first_name" class="m-0 p-0">
                <el-input
                  v-model="form.first_name"
                  :class="{ 'is-invalid': form.errors.has('first_name') }"
                  type="text"
                  name="name"
                />
                <has-error :form="form" field="first_name" />
              </el-form-item>
            </div>
          </div>
          <!-- Middle name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('middle_name') }}</label>
            <div class="col-md-7">
              <!-- Middle name -->
              <el-form-item prop="middle_name" class="m-0 p-0">
                <el-input
                  v-model="form.middle_name"
                  :class="{ 'is-invalid': form.errors.has('middle_name') }"
                  type="text"
                  name="name"
                />
                <has-error :form="form" field="middle_name" />
              </el-form-item>
            </div>
          </div>
          <!-- Last name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
            <div class="col-md-7">
              <!-- Last name -->
              <el-form-item prop="last_name" class="m-0 p-0">
                <el-input
                  v-model="form.last_name"
                  :class="{ 'is-invalid': form.errors.has('last_name') }"
                  type="text"
                  name="name"
                />
                <has-error :form="form" field="last_name" />
              </el-form-item>
            </div>
          </div>
          <!-- Email row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-7">
              <!-- Email -->
              <el-form-item prop="email" class="m-0 p-0">
                <el-input
                  v-model="form.email"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                  type="email"
                  name="email"
                  autocomplete="off"
                />
                <has-error :form="form" field="email" />
              </el-form-item>
            </div>
          </div>
          <!-- Password row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
            <div class="col-md-7">
              <!-- Password -->
              <el-form-item prop="password" class="m-0 p-0">
                <el-input
                  v-model="form.password"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                  type="password"
                  name="password"
                  autocomplete="off"
                />
                <has-error :form="form" field="password" />
              </el-form-item>
            </div>
          </div>
          <!-- Confirm password row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
            <div class="col-md-7">
              <!-- Confirm password -->
              <el-form-item prop="password_confirmation" class="m-0 p-0">
                <el-input
                  v-model="form.password_confirmation"
                  :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
                  type="password"
                  name="password_confirmation"
                  autocomplete="off"
                />
                <has-error :form="form" field="password_confirmation" />
              </el-form-item>
            </div>
          </div>
          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Reset Button -->
              <el-button class="el-button el-button--default" @click="resetForm('form')">
                {{ $t('reset') }}
              </el-button>
              <!-- Submit Button -->
              <el-button :loading="form.busy" class="el-button el-button--primary" @click="submitForm()">
                {{ $t('create') }}
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

export default {
  middleware: 'auth',

  data () {
    return {
      form: new Form({
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: ''
      })
    }
  },
  head () {
    return { title: this.$t('create') }
  },
  methods: {
    async submitForm () {
      try {
        await this.form.post('/users')
        this.$notify.success({
          title: 'Success',
          message: 'User ' + this.form.first_name + ' ' + this.form.middle_name + ' successfully created.'
        })
        // Redirect users.
        await this.$router.push({ name: 'users-list' })
      } catch (e) {
        this.$notify.error({
          title: 'Error',
          message: e.message
        })
      }
    },
    resetForm (formName) {
      this.$refs[formName].resetFields()
    }
  }
}
</script>

<style scoped>

</style>
