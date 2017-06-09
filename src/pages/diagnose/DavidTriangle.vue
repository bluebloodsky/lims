<template>
  <div class="box">
    <div class="h">
      <i class="mdi iconfont icon-host">
        </i>
      <span>大卫三角诊断</span>
    </div>
    <div class="b">
      <canvas id="bg" width="550" height="300"></canvas>
      <canvas id="fg" width="550" height="300"></canvas>
    </div>
  </div>
</template>
<script>
import Diagnose from '@/tool/Diagnose'
export default {
  props: {
    sourceData: {
      type: Object,
      default () {
        return {
          "H2": 11,
          "CH4": 15,
          "C2H2": 15,
          "C2H4": 15,
          "C2H6": 25
        }
      }
    }
  },
  data() {
    return {
      davidTriangle: null,
      resultCanvas: null
    }
  },
  mounted() {
    let canvas = document.getElementById("bg")
    this.resultCanvas = document.getElementById("fg")
    this.davidTriangle = new Diagnose.drawDavidTriangle()
    this.davidTriangle.InitTriangle(canvas)
    this.davidTriangle.drawResult(this.resultCanvas, this.sourceData)
  }
}
</script>
<style scoped>
canvas {
  position: absolute;
  left: 0;
  top: 0;
}
.box {
  width: 550px;
  height: 350px;
  border-right: #dfdfdf 1px solid;
  border-bottom: #dfdfdf 1px solid;
  float: left;
  position: relative;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  /* Firefox */
  -webkit-box-sizing: border-box;
  /* Safari */
}
</style>
