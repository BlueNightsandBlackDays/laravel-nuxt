<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card :title="$t('verify_email')">
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
              <!-- Send verification link Button -->
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="send()">
                {{ $t('send_verification_link') }}
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

  metaInfo () {
    return { title: this.$t('verify_email') }
  },

  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  created () {
    if (this.$route.query.email) {
      this.form.email = this.$route.query.email
    }
  },

  methods: {
    send () {
      this.form.post('/email/resend').then(({ data }) => {
        this.status = data.status
        this.form.reset()
      })
    }
  }
}
</script>
