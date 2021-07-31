<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5">
            {{ $t('update_user') }}
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
        <el-form ref="ruleForm" :model="ruleForm" :rules="rules" class="demo-ruleForm">
          <!-- First name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
            <div class="col-md-7">
              <!-- First name -->
              <el-form-item prop="first_name">
                <el-input
                  v-model="ruleForm.first_name"
                  type="text"
                  name="name"
                />
              </el-form-item>
            </div>
          </div>
          <!-- Middle name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('middle_name') }}</label>
            <div class="col-md-7">
              <!-- Middle name -->
              <el-form-item prop="middle_name">
                <el-input
                  v-model="ruleForm.middle_name"
                  type="text"
                  name="name"
                />
              </el-form-item>
            </div>
          </div>
          <!-- Last name row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
            <div class="col-md-7">
              <!-- Last name -->
              <el-form-item prop="last_name">
                <el-input
                  v-model="ruleForm.last_name"
                  type="text"
                  name="name"
                />
              </el-form-item>
            </div>
          </div>
          <!-- Email row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-7">
              <!-- Email -->
              <el-form-item prop="email">
                <el-input
                  v-model="ruleForm.email"
                  type="email"
                  name="email"
                  autocomplete="off"
                />
              </el-form-item>
            </div>
          </div>
          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Reset Button -->
              <el-button class="el-button el-button--default" @click="resetForm('ruleForm')">
                {{ $t('reset') }}
              </el-button>
              <!-- Submit Button -->
              <el-button class="el-button el-button--primary" @click="submitForm('ruleForm')">
                {{ $t('update') }}
              </el-button>
            </div>
          </div>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',

  data () {
    return {
      ruleForm: {
        first_name: '',
        middle_name: '',
        last_name: '',
        email: ''
      },
      rules: {
        first_name: [{ required: true, message: 'Please type name', trigger: 'blur' }],
        middle_name: [{ required: true, message: 'Please type name', trigger: 'blur' }],
        last_name: [{ required: true, message: 'Please type name', trigger: 'blur' }],
        email: [{ required: true, message: 'Please type email', trigger: 'blur' }]
      }
    }
  },
  head () {
    return { title: this.$t('update') }
  },
  computed: mapGetters({
    user: 'users/user',
    loading: 'users/user_loading'
  }),
  async mounted () {
    await this.getData()
  },
  methods: {
    async getData () {
      await this.$store.dispatch('users/fetchUser', { id: this.$route.params.id })
      this.ruleForm.id = this.user.id
      this.ruleForm.first_name = this.user.first_name
      this.ruleForm.middle_name = this.user.middle_name
      this.ruleForm.last_name = this.user.last_name
      this.ruleForm.email = this.user.email
    },
    submitForm (formName) {
      this.$refs[formName].validate(async (valid) => {
        if (valid) {
          try {
            await this.$store.dispatch('users/updateUser', this.ruleForm)
            this.$notify.success({
              title: 'Success',
              message: 'User successfully updated.'
            })
          } catch (e) {
            this.$notify.error({
              title: 'Error',
              message: e.message
            })
          }
        } else {
          return false
        }
      })
    },
    resetForm (formName) {
      this.$refs[formName].resetFields()
    }
  }
}
</script>

<style scoped>

</style>
