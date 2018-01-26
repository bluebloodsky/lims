<template>
  <g @mousedown.prevent="mousedown">
    <polygon :points="d_sel" :stroke="selected?'#22C':'transparent'" stroke-dasharray="5,5" fill="transparent"></polygon>
    <path :d="d"></path>
  </g>
</template>
<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    },
    selected: {
      type: Boolean,
      required: true,
      default: false
    },
    arrowLength: {
      type: Number,
      default: 8
    },
    arrowWidth: {
      type: Number,
      default: 5
    },
    lineWidth: {
      type: Number,
      default: 2
    }
  },
  computed: {
    d_sel() {
      var startPoint = this.item.startPoint,
        endPoint = this.item.endPoint
      var angle = Math.atan((endPoint[1] - startPoint[1]) / (endPoint[0] - startPoint[0]))
      var lineXdiff = (this.lineWidth + 10) / 2 * Math.sin(angle)
      var lineYdiff = (this.lineWidth + 10) / 2 * Math.cos(angle)
      var points = new Array(4)
      points[0] = [startPoint[0] - lineXdiff, startPoint[1] + lineYdiff]
      points[1] = [startPoint[0] + lineXdiff, startPoint[1] - lineYdiff]
      points[2] = [endPoint[0] + lineXdiff, endPoint[1] - lineYdiff]
      points[3] = [endPoint[0] - lineXdiff, endPoint[1] + lineYdiff]

      return points
      /*
      var path = "M" + points[3][0] + " " + points[3][1] + " "
      points.map(point => {
        path += "L" + point[0] + " " + point[1] + " "
      })
      path += "Z"
      return path
      */
    },
    d() {
      var startPoint = this.item.startPoint,
        endPoint = this.item.endPoint

      var coefficientX = (startPoint[0] - endPoint[0] > 0) ? -1 : 1

      var angle = Math.atan((endPoint[1] - startPoint[1]) / (endPoint[0] - startPoint[0]))

      var lineXdiff = this.lineWidth / 2 * Math.sin(angle)
      var lineYdiff = this.lineWidth / 2 * Math.cos(angle)
      var arrowLengthXDiff = coefficientX * this.arrowLength * Math.cos(angle)
      var arrowLengthYDiff = coefficientX * this.arrowLength * Math.sin(angle)
      var arrowWidthXDiff = coefficientX * this.arrowWidth * Math.sin(angle)
      var arrowWidthYDiff = coefficientX * this.arrowWidth * Math.cos(angle)

      var points = new Array(7)
      points[0] = [startPoint[0] - lineXdiff, startPoint[1] + lineYdiff]
      points[6] = [startPoint[0] + lineXdiff, startPoint[1] - lineYdiff]
      points[3] = endPoint

      points[1] = [points[3][0] - arrowLengthXDiff * 0.8 - lineXdiff, points[3][1] - arrowLengthYDiff * 0.8 + lineYdiff]
      points[5] = [points[3][0] - arrowLengthXDiff * 0.8 + lineXdiff, points[3][1] - arrowLengthYDiff * 0.8 - lineYdiff]

      points[2] = [points[3][0] - arrowLengthXDiff - arrowWidthXDiff, points[3][1] - arrowLengthYDiff + arrowWidthYDiff]
      points[4] = [points[3][0] - arrowLengthXDiff + arrowWidthXDiff, points[3][1] - arrowLengthYDiff - arrowWidthYDiff]

      var path = "M" + points[6][0] + " " + points[6][1] + " "
      points.map(point => {
        path += "L" + point[0] + " " + point[1] + " "
      })
      path += "Z"
      return path
    }
  },
  methods: {
    mousedown(e) {
      if (e.button == 0) { //左键抓取
        this.$emit('grab', e, JSON.parse(JSON.stringify(this.item)))
      }
    }
  }
}

</script>
<style scoped>
g {
  cursor: pointer
}

</style>
