<template>
  <path :d="d" />
</template>
<script>
export default {
  props: {
    item: {
      type: Object ,
      required: true
    },
    arrowLength:{
      type:Number,
      default:8
    },
    arrowWidth:{
      type:Number,
      default:5
    },
    lineWidth:{
      type:Number,
      default:2
    }
  },
  computed: {
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

      points[1] = [points[3][0] - arrowLengthXDiff - lineXdiff, points[3][1] - arrowLengthYDiff + lineYdiff]
      points[5] = [points[3][0] - arrowLengthXDiff + lineXdiff, points[3][1] - arrowLengthYDiff - lineYdiff]

      points[2] = [points[3][0] - arrowLengthXDiff - arrowWidthXDiff, points[3][1] - arrowLengthYDiff + arrowWidthYDiff]
      points[4] = [points[3][0] - arrowLengthXDiff + arrowWidthXDiff, points[3][1] - arrowLengthYDiff - arrowWidthYDiff]
      /*
      var point2, point3, point5, point6, point7
      var point1 = [ - lineWidth / 2], //起点
        point4 =  endPoint

      

      if (point4[0] < point1[0]) {
        point2 = [point4[0] + arrowLength * Math.cos(angle), point4[1] + arrowLength * Math.sin(angle)]
      } else {
        point2 = [point4[0] - arrowLength * Math.cos(angle), point4[1] - arrowLength * Math.sin(angle)]
      }
      point3 = [point2[0] + arrowWidth * Math.sin(angle), point2[1] - arrowWidth * Math.cos(angle)]
      point5 = [point2[0] - arrowWidth * Math.sin(angle), point2[1] + arrowWidth * Math.cos(angle)]
      point6 = [point2[0] - lineWidth * Math.sin(angle), point2[1] + lineWidth * Math.cos(angle)]
      point7 = [point1[0] - lineWidth * Math.sin(angle), point1[1] + lineWidth * Math.cos(angle)]

      var points = [point2, point3, point4, point5, point6, point7, point1]
      */
      var path = "M" + points[6][0] + " " + points[6][1] + " "
      points.map(point => {
        path += "L" + point[0] + " " + point[1] + " "
      })
      path += "Z"
      return path
    }
  }
}
</script>

<style scoped>
  path {
  cursor: pointer
}
</style>
