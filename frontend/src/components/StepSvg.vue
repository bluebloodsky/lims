<template>
  <g @mousedown.prevent="mousedown" @contextmenu.prevent @dblclick="dblclick">
    <rect :x="item.point[0]" :y="item.point[1]" rx="5" ry="5" :width="width" :height="height"></rect>
    <text :x="item.point[0] + 20" :y="item.point[1] + 25">{{item.name_cn}} {{item.alias?'('+ item.alias + ')':''}}</text>
    <polygon :points="d_sel" stroke="#22C" stroke-dasharray="5,5" fill="none" v-show="selected"/>
  </g>
</template>
<script type="text/javascript">
import { copyObject } from '@/shared/util'

export default { 
  props: {
    item: {
      type: Object,
      required: true
    },
    selected: {
      type: Boolean,
      default: false
    },
    height: {
      type: Number,
      default: 40
    },
    width: {
      type: Number,
      default: 200
    }
  },
  replace: true,
  computed: {
    d_sel() {
      var lineXdiff = 5
      var lineYdiff = 5
      var points = new Array(4)
      points[0] = [this.item.point[0] - lineXdiff, this.item.point[1] - lineYdiff]
      points[1] = [this.item.point[0] + this.width + lineXdiff, this.item.point[1] - lineYdiff]
      points[2] = [this.item.point[0] + this.width + lineXdiff, this.item.point[1] + this.height + lineYdiff]
      points[3] = [this.item.point[0] - lineXdiff, this.item.point[1] + this.height + lineYdiff]
      return points
    }
  },
  methods: {
    mousedown(e) {
      if (e.button == 0) { //左键抓取
        this.$emit('grab', e, copyObject(this.item))
      } else if (e.button == 2) { //右键菜单
        this.$emit('rightClick', e ,copyObject(this.item))
      }
    },
    dblclick(e) {
      this.$emit('dblclick', e, copyObject(this.item))
    }
  }
}

</script>
<style scoped>
rect {
  fill: #ccc;
  opacity: 0.5;
}

g {
  cursor: pointer
}

</style>
