<template>
  <card :title="$t('your_password')">
    <el-form class="demo-form">
      <alert-success :form="form" :message="$t('password_updated')" />
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
          <!-- Update Button -->
          <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="update()">
            {{ $t('update') }}
          </el-button>
        </div>
      </div>
    </el-form>
  </card>
</template>

<script>
import Form from 'vform'

export default {
  scrollToTop: false,

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    }),
    mustVerifyEmail: false
  }),

  head () {
    return { title: this.$t('settings') }
  },

  methods: {
    update () {
      this.form.patch('/settings/password').then(() => {
        this.form.reset()
      })
    }
  }
}
</script>
