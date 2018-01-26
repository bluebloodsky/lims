<template>
  <g @mousedown.prevent="mousedown" @contextmenu.prevent>
    <rect :x="item.point[0]" :y="item.point[1]" rx="5" ry="5"></rect>
    <text :x="item.point[0] + 22" :y="item.point[1] + 25">{{item.label}}</text>
    <polygon :points="d_sel" stroke="#22C" stroke-dasharray="5,5" fill="none" v-show="selected"/>
  </g>
</template>
<script type="text/javascript">
// A resusable polygon graph component
export default { // 定义并注册一个图形组件
  props: ['item','selected'],
  replace: true,
  computed: {
    d_sel() {
      var lineXdiff = 5
      var lineYdiff = 5
      var points = new Array(4)
      points[0] = [this.item.point[0] - lineXdiff, this.item.point[1] - lineYdiff]
      points[1] = [this.item.point[0] + 150 + lineXdiff, this.item.point[1] - lineYdiff]
      points[2] = [this.item.point[0] + 150 + lineXdiff, this.item.point[1] + 40 + lineYdiff]
      points[3] = [this.item.point[0] - lineXdiff, this.item.point[1] + 40 + lineYdiff]
      return points
/*
      var path = "M" + points[3][0] + " " + points[3][1] + " "
      points.map(point => {
        path += "L" + point[0] + " " + point[1] + " "
      })
      path += "Z"
      return path
      */
    }
  },
  methods: {
    mousedown(e) {
      if (e.button == 0) { //左键抓取
        this.$emit('grab', e, JSON.parse(JSON.stringify(this.item)))
      } else if (e.button == 2) { //右键菜单
        this.$emit('rightClick', e, JSON.parse(JSON.stringify(this.item)))
      }
    }
  }
}

</script>
<style scoped>
rect {
  fill: #ccc;
  height: 40px;
  width: 150px;
  opacity: 0.5;
}

g {
  cursor: pointer
}

</style>
