<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <!-- Verify email address -->
      <card v-if="mustVerifyEmail" :title="$t('register')">
        <div class="alert alert-success" role="alert">
          {{ $t('verify_email_address') }}
        </div>
      </card>

      <!-- Register -->
      <card v-else :title="$t('register')">
        <el-form class="demo-form">
          <!-- First name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-7">
              <!-- First name -->
              <el-input
                v-model="form.first_name"
                :class="{ 'is-invalid': form.errors.has('first_name') }"
                type="text"
                name="name"
              />
              <has-error :form="form" field="first_name" />
            </div>
          </div>

          <!-- Middle name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('middle_name') }}</label>
            <div class="col-md-7">
              <!-- Middle name -->
              <el-input
                v-model="form.middle_name"
                :class="{ 'is-invalid': form.errors.has('middle_name') }"
                type="text"
                name="name"
              />
              <has-error :form="form" field="middle_name" />
            </div>
          </div>

          <!-- Email row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-7">
              <!-- Email -->
              <el-input
                v-model="form.email"
                :class="{ 'is-invalid': form.errors.has('email') }"
                type="email"
                name="email"
                autocomplete="off"
              />
              <has-error :form="form" field="email" />
            </div>
          </div>

          <!-- Password row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
            <div class="col-md-7">
              <!-- Password -->
              <el-input
                v-model="form.password"
                :class="{ 'is-invalid': form.errors.has('password') }"
                type="password"
                name="password"
                autocomplete="off"
              />
              <has-error :form="form" field="password" />
            </div>
          </div>

          <!-- Confirm password row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
            <div class="col-md-7">
              <!-- Confirm password -->
              <el-input
                v-model="form.password_confirmation"
                :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
                type="password"
                name="password_confirmation"
                autocomplete="off"
              />
              <has-error :form="form" field="password_confirmation" />
            </div>
          </div>

          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Register Button -->
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="register()">
                {{ $t('register') }}
              </el-button>
            </div>
            <login-with-github />
          </div>
        </el-form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  data: () => ({
    form: new Form({
      first_name: '',
      middle_name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }),
    mustVerifyEmail: false
  }),

  head () {
    return { title: this.$t('register') }
  },

  methods: {
    async register () {
      let data

      // Register the user.
      try {
        const response = await this.form.post('/register')
        data = response.data
      } catch (e) {
        return
      }

      // Must verify email fist.
      if (data.status) {
        this.mustVerifyEmail = true
      } else {
        // Log in the user.
        const { data: { token } } = await this.form.post('/login')

        // Save the token.
        await this.$store.dispatch('auth/saveToken', { token })

        // Update the user.
        await this.$store.dispatch('auth/updateUser', { user: data })

        // Redirect home.
        await this.$router.push({ name: 'attendances-view' })
      }
    }
  }
}
</script>
