<template>
  <div>
    Hello
  </div>
</template>

<script>
import axios from 'axios'
import { Line } from 'vue-chartjs'

export default {
  extends: Line,
  mounted () {
    // this.renderChart(data, options)
    const startTime = new Array(1000)
    // const response = this.$store.dispatch('attendance/fetchChartAttendance', { id: this.$route.params.id })
    const response = axios.get(`/attendances/show-chart/${this.$route.params.id}`)
    const chartData = response.data
    if (chartData) {
      chartData.forEach((element) => {
        startTime.push(element.time_start)
      })
      this.renderChart({
        labels: startTime,
        datasets: [{
          label: 'Attendance',
          backgroundColor: '#FC2525',
          data: startTime
        }]
      }, { responsive: true, maintainAspectRatio: false })
    } else {
      this.$notify.error({
        title: 'Error',
        message: 'No data'
      })
    }
  }
}
</script>

<style scoped>

</style>
