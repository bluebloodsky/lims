<template>
  <div>
    <div class="box david-box">
      <div class="h">
        <i class="mdi iconfont icon-host">
        </i>
        <span>大卫三角诊断</span>
      </div>
      <div class="b">
        <canvas id="david-bg" width="550" height="300"></canvas>
        <canvas id="david-fg" width="550" height="300"></canvas>
      </div>
    </div>
    <div class="box tri-box">
      <div class="h">
        <i class="mdi iconfont icon-host">
        </i>
        <span>改良三比值法诊断</span>
      </div>
      <div class="b">
        <div class="desc-item-box">
          <label>故障类型判断</label>
          <span>{{triResult.conclusion}}</span>
        </div>
        <div class="desc-item-box">
          <label>故障实例（参考）</label>
          <span>{{triResult.instruction}}</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Diagnose from '@/tool/Diagnose'
export default {
  props: {
    sourceData: {
      default: function() {
        return {
          H2: 11,
          C2H2: 22,
          CH4: 33,
          C2H4: 55,
          C2H6: 77
        }
      }
    }
  },
  watch: {
    sourceData() {
      this.davidTriangle.drawResult(this.resultCanvas, this.sourceData)
    }
  },
  computed: {
    triResult() {
      return Diagnose.getTriRatioResult(this.sourceData)
    }
  },

  data() {
    return {
      davidTriangle: null,
      resultCanvas: null
    }
  },
  mounted() {
    let canvas = document.getElementById("david-bg")
    this.resultCanvas = document.getElementById("david-fg")
    this.davidTriangle = new Diagnose.drawDavidTriangle()
    this.davidTriangle.InitTriangle(canvas)
  }
}
</script>
<style scoped>
canvas {
  position: absolute;
  left: 0;
  top: 0;
}

.david-box {
  width: 580px;
  height: 350px;
}

.tri-box {
  width: 300px;
  height: 350px;
}
</style>
