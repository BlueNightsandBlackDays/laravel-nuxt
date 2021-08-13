<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card :title="$t('reset_password')">
        <el-form class="demo-ruleForm">
          <alert-success :form="form" :message="status" />

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
              <!-- Reset password Button -->
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="reset()">
                {{ $t('reset_password') }}
              </el-button>
            </div>
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
    status: '',
    form: new Form({
      token: '',
      email: '',
      password: '',
      password_confirmation: ''
    })
  }),

  head () {
    return { title: this.$t('reset_password') }
  },

  created () {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.params.token
  },

  methods: {
    reset () {
      this.form.post('/password/reset').then(({ data }) => {
        this.status = data.status
        this.form.reset()
      })
    }
  }
}
</script>
