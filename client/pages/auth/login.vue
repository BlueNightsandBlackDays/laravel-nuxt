<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <!-- Login -->
      <card :title="$t('login')">
        <el-form class="demo-ruleForm">
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

          <!-- Remember Me -->
          <div class="form-group row">
            <div class="col-md-3" />
            <div class="col-md-7 d-flex">
              <el-checkbox v-model="remember" name="remember">
                {{ $t('remember_me') }}
              </el-checkbox>

              <nuxt-link :to="{ name: 'password.request' }" class="small ml-auto my-auto">
                {{ $t('forgot_password') }}
              </nuxt-link>
            </div>
          </div>

          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Login Button -->
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="login()">
                {{ $t('login') }}
              </el-button>
            </div>

            <!-- GitHub Login Button -->
            <login-with-github />
          </div>
        </el-form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  middleware: 'guest',

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  head () {
    return { title: this.$t('login') }
  },

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async login () {
      let data

      // Submit the form.
      try {
        const response = await this.form.post('/login')
        data = response.data
      } catch (e) {
        return
      }

      // Save the token.
      await this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Fetch user permissions and save it on localstorage.
      const response = await axios.get(`/permissions/fetchPermissions/${this.user.id}`)
      localStorage.setItem('permissions', JSON.stringify(response.data))

      // Redirect home.
      // await this.$router.push({ name: 'home' })
      await this.$router.push({ name: 'attendances-view' })
    }
  }
}
</script>
