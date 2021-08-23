<template>
  <div>
    <sidebar />
    <div class="content ht-100v pd-0">
      <navbar />
      <div class="content-body">
        <nuxt />
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import Sidebar from '~/components/Sidebar'

export default {
  components: {
    Navbar,
    Sidebar
  }
}
</script>
