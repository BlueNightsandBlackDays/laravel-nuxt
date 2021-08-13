<template>
  <!-- Role container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <el-page-header
          title=""
          :content="$t('create_roles')"
          @back="goBack"
        />
      </div>

      <!-- Card-body -->
      <div class="card-body col-lg-12">
        <el-form ref="form" :model="form" :rules="rules" class="demo-form">
          <!-- Role row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-7">
              <!-- Role name -->
              <el-form-item prop="name" class="m-1 p-0">
                <el-input
                  v-model="form.name"
                  type="text"
                  name="name"
                />
              </el-form-item>
            </div>
          </div>

          <!-- Permission row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('permissions') }}</label>
            <div class="col-md-7">
              <!-- Select permissions -->
              <el-form-item prop="permission" class="m-1 p-0">
                <el-select
                  v-model="form.selected_permissions"
                  multiple
                  :loading="loading"
                  size="large"
                  :placeholder="$t('select_permissions')"
                  @focus="getData"
                >
                  <el-option
                    v-for="permission in permissions.data"
                    :key="permission.id"
                    :label="permission.name"
                    :value="permission.id"
                  />
                </el-select>
              </el-form-item>
            </div>
          </div>

          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Reset Button -->
              <el-button class="el-button el-button--medium el-button--default" @click="resetForm('form')">
                {{ $t('reset') }}
              </el-button>
              <!-- Submit Button -->
              <el-button :loading="form.busy" class="el-button el-button--medium el-button--primary" @click="createRole('form')">
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
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  middleware: 'auth',
  data () {
    return {
      form: new Form({
        name: '',
        selected_permissions: []
      }),
      loader: false,
      rules: {
        name: [{ required: true, message: 'Please enter a new role', trigger: 'blur' }]
      }
    }
  },
  computed: mapGetters({
    permissions: 'permissions/permissions',
    loading: 'permissions/loading',
    create_loading: 'roles/create_loading'
  }),
  methods: {
    goBack () {
      this.$router.push({ name: 'roles-list' })
    },
    getData () {
      this.$store.dispatch('permissions/fetchPermissions', { limit: 100 })
    },
    createRole (formRule) {
      this.$refs[formRule].validate(async (valid) => {
        if (valid) {
          try {
            await axios.post('/roles/create', this.form)
            this.$notify.success({
              title: 'Success',
              message: 'Role successfully created.'
            })
            // Redirect users.
            await this.$router.push({ name: 'roles-list' })
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
    resetForm (formRule) {
      this.$refs[formRule].resetFields()
    }
  }
}
</script>

<style scoped>

</style>
