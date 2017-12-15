<template>
  <g @mousedown.prevent="mousedown" @contextmenu.prevent>
    <rect :x="item.point[0]" :y="item.point[1]" rx="5" ry="5"></rect>
    <text :x="item.point[0] + 22" :y="item.point[1] + 25">{{item.label}}</text>
  </g>
</template>
<script type="text/javascript">
// A resusable polygon graph component
export default { // 定义并注册一个图形组件
  props: ['item'],
  replace: true,
  methods: {
    mousedown(e) {
      if (e.button == 0) { //左键抓取
        this.$emit('grab', e, JSON.parse(JSON.stringify(this.item)))
      }
      else if(e.button == 2){ //右键菜单
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
  opacity: 0.9;
}

g {
  cursor: pointer
}

</style>
