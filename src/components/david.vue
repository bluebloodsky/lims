<template>
  <canvas></canvas>
</template>
<script>
import polygraph from '@/components/polygraph'
export default {
  data() {
      return {
        stats: [{
          label: 'A',
          value: 100
        }, {
          label: 'B',
          value: 100
        }, {
          label: 'C',
          value: 100
        }, {
          label: 'D',
          value: 100
        }, {
          label: 'E',
          value: 100
        }, {
          label: 'F',
          value: 100
        }],
        newLabel: ''
      }
    },
    components: {
      polygraph
    },
    methods: {
      add: function(e) {
        e.preventDefault()
        if (!this.newLabel) return
        this.stats.push({
          label: this.newLabel,
          value: 100
        })
        this.newLabel = ''
      },
      remove: function(stat) {
        if (this.stats.length > 3) {
          this.stats.splice(this.stats.indexOf(stat), 1)
        } else {
          alert('Can\'t delete more!')
        }
      }
    }
}
</script>
<style scoped>

</style>
