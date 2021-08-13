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

          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Send password reset link Button -->
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="send()">
                {{ $t('send_password_reset_link') }}
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
  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  head () {
    return { title: this.$t('reset_password') }
  },

  methods: {
    send () {
      this.form.post('/password/email').then(({ data }) => {
        this.status = data.status
        this.form.reset()
      })
    }
  }
}
</script>
