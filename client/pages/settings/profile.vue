<template>
  <card :title="$t('your_info')">
    <el-form class="demo-ruleForm">
      <alert-success :form="form" :message="$t('info_updated')" />

      <!-- First name row -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
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

      <!-- Last name row -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
        <div class="col-md-7">
          <!-- Last name -->
          <el-input
            v-model="form.last_name"
            :class="{ 'is-invalid': form.errors.has('last_name') }"
            type="text"
            name="name"
          />
          <has-error :form="form" field="last_name" />
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
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  data: () => ({
    form: new Form({
      first_name: '',
      middle_name: '',
      last_name: '',
      email: ''
    })
  }),

  head () {
    return { title: this.$t('settings') }
  },

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach((key) => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    update () {
      this.form.patch('/settings/profile').then(({ data: user }) => {
        this.$store.dispatch('auth/updateUser', { user })
      })
    }
  }
}
</script>
