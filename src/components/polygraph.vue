<template>
  <g>
    <polygon :points="polygon_points"></polygon>
    <circle cx="100" cy="100" r="80"></circle>
    <text v-for="(stat, index) in stats" :x="text_points[index].x" :y="text_points[index].y">
      {{stat.label}}
    </text>
  </g>
</template>
<script>
export default {
  props: ['stats'],
  computed: {
    polygon_points() {
      let total = this.stats.length
      console.log(this.stats)
      return this.stats.map((stat, i) => {
        let point = this.valueToPoint(stat, i, total)
        return point.x + ',' + point.y
      }).join(' ')
    },
    text_points() {
      let total = this.stats.length
      let ret = []
      this.stats.map((stat, i) => {
        ret.push(this.valueToPoint(100 + 10,
          i,
          total
        ))
      })
      return ret
    }
  },
  methods: {
    valueToPoint(value, index, total) {
      var x = 0
      var y = -value * 0.8
      var angle = Math.PI * 2 / total * index
      var cos = Math.cos(angle)
      var sin = Math.sin(angle)
      var tx = x * cos - y * sin + 100
      var ty = x * sin + y * cos + 100
      return {
        x: tx,
        y: ty
      }
    }
  }

}
</script>
<style scoped>
polygon {
  fill: #42b983;
  opacity: .75;
}

circle {
  fill: transparent;
  stroke: #999;
}

text {
  font-family: Helvetica Neue, Arial, sans-serif;
  font-size: 10px;
  fill: #666;
}
</style>
