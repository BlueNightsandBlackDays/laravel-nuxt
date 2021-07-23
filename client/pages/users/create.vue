<template>
  <div class="container pd-x-0">
    <card>
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5">
            Create Users
          </h5>
        </div>
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="light" content="back to users" placement="top">
            <nuxt-link
              :to="{ name: 'users-list'}"
              class="nav-link el-icon-back"
            >
              Back
            </nuxt-link>
          </el-tooltip>
        </div>
      </div>
      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <form @submit.prevent="register" @keydown="form.onKeydown($event)">
          <!-- First name -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
            <div class="col-md-7">
              <el-input
                v-model="form.first_name"
                :class="{ 'is-invalid': form.errors.has('first_name') }"
                type="text"
                name="first_name"
              />
              <has-error :form="form" field="first_name" />
            </div>
          </div>

          <!-- Last name -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
            <div class="col-md-7">
              <el-input
                v-model="form.last_name"
                :class="{ 'is-invalid': form.errors.has('last_name') }"
                type="text"
                name="last_name"
              />
              <has-error :form="form" field="last_name" />
            </div>
          </div>

          <!-- Email -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-7">
              <el-input
                v-model="form.email"
                :class="{ 'is-invalid' :form.errors.has('email') }"
                type="email"
                name="email"
              />
              <has-error :form="form" field="email" />
            </div>
          </div>

          <!-- Password -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
            <div class="col-md-7">
              <el-input
                v-model="form.password"
                :class="{ 'is-invalid': form.errors.has('password') }"
                type="password" name="password"
              />
              <has-error :form="form" field="password" />
            </div>
          </div>

          <!-- Password Confirmation -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
            <div class="col-md-7">
              <el-input
                v-model="form.password_confirmation"
                :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
                type="password"
                name="password_confirmation"
              />
              <has-error :form="form" field="password_confirmation" />
            </div>
          </div>

          <!-- Buttons -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Reset Button -->
              <el-button class="el-button el-button--default" @click="resetForm('ruleForm')">
                {{ $t('Reset') }}
              </el-button>
              <!-- Submit Button -->
              <el-button :loading="form.busy" class="el-button el-button--primary" @click="submitForm('ruleForm')">
                {{ $t('Create') }}
              </el-button>
            </div>
          </div>
        </form>
      </div>
    </card>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  data: () => ({
    form: new Form({
      first_name: '',
      last_name: '',
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
        this.$store.dispatch('auth/saveToken', { token })

        // Update the user.
        await this.$store.dispatch('auth/updateUser', { user: data })

        // Redirect home.
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>

<style scoped>

</style>
