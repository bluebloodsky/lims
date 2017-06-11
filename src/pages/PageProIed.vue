<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-config-doc">
        </i>
        <span>采集参数配置</span>
        <div class="right-btn">
          <el-button type="text" @click="">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
      </div>
    </div>
      <div class="box top-box">
        <div class="h">
          <i class="mdi iconfont icon-debug">
        </i>
          <span>指令下达</span>
        </div>
        <div class="b">
        </div>
      </div>
      <div class="box bottom-box">
        <div class="h">
          <i class="mdi iconfont icon-log">
        </i>
          <span>日志信息</span>
          <div class="right-btn">
            <span>每次刷新行数</span>
            <input></input>
            <el-button type="text" @click="">
              <i class="iconfont icon-search"></i>
            </el-button>
          </div>
        </div>
        <div class="b">
        </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
export default {
  data() {
    return {
      fields: [{
        name: 'file_name',
        caption: '文件名'
      }, {
        name: 'size',
        caption: '大小'
      }, {
        name: 'update_time',
        caption: '更新时间'
      }]
    }
  },
  computed: mapGetters({
    sysInfo: 'sysInfo',
    devStatus: 'status',
    currentData: 'currentData'
  })
}
</script>
<style scoped>

.left-box {
  width: 30%;
  height: 100%;
}

.top-box {
  width: 70%;
  height: 30%
}

.bottom-box {
  width: 70%;
  height: 70%
}

</style>
