<template>
  <!-- Container -->
  <div class="container pd-x-0">
    <div class="card">
      <!-- Card-header -->
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h5 class="mg-b-5 mt-2">
            {{ $t('update_roles') }}
          </h5>
        </div>
        <div class="d-none d-md-block">
          <el-tooltip class="item" effect="light" content="back to users" placement="top">
            <nuxt-link
              :to="{ name: 'roles-list'}"
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
        <el-form ref="form" :model="form" :rules="rules" class="demo-form">
          <!-- Role row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-7">
              <!-- Role name -->
              <el-form-item prop="name" class="m-1 p-0">
                <el-input
                  v-model="form.name"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                  type="text"
                  name="name"
                />
                <has-error :form="form" field="name" />
              </el-form-item>
            </div>
          </div>

          <!-- Permission row -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('permissions') }}</label>
            <div class="col-md-7">
              <!-- Role name -->
              <el-form-item prop="permission" class="m-1 p-0">
                <el-select
                  v-model="form.selected_permissions"
                  multiple
                  placeholder="Select Permissions"
                  @focus="getPermission"
                >
                  <el-option
                    v-for="permission in permissions.data"
                    :key="permission.id"
                    :label="permission.name"
                    :value="permission.id"
                  />
                </el-select>
                <div> {{ form.selected_permissions }} </div>
              </el-form-item>
            </div>
          </div>

          <!-- Buttons row -->
          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex justify-content-end">
              <!-- Reset Button -->
              <el-button class="el-button el-button--default" @click="resetForm('form')">
                {{ $t('reset') }}
              </el-button>
              <!-- Submit Button -->
              <el-button :loading="form.busy" class="el-button el-button--primary" @click="updateRole('form')">
                {{ $t('Update') }}
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
        name: [{ required: true, message: 'Please enter a new role', trigger: 'change' }]
      }
    }
  },
  head () {
    return { title: this.$t('update') }
  },
  computed: mapGetters({
    role: 'roles/role',
    loading: 'roles/role_loading',
    permissions: 'permissions/permissions',
    permission_loading: 'permissions/loading'
  }),
  async mounted () {
    await this.getRole()
  },
  methods: {
    async getRole () {
      await this.$store.dispatch('roles/fetchRole', { id: this.$route.params.id })
      this.form.id = this.role.data.id
      this.form.name = this.role.data.name
    },
    getPermission () {
      this.$store.dispatch('permissions/fetchPermissions', { limit: 100 })
    },
    updateRole (formRule) {
      this.$refs[formRule].validate(async (valid) => {
        if (valid) {
          try {
            await this.$store.dispatch('roles/updateRole', this.form)
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
