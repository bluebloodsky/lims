<template>
  <div>
    <div class="h">
      <i class="mdi iconfont icon-log">
        </i>
      <span>日志信息</span>
      <div class="right-btn">
        <span>每次刷新行数</span>
        <input v-model="limitNum"></input>
        <el-button type="text" @click="getLogContent">
          <i class="iconfont icon-search"></i>
        </el-button>
      </div>
    </div>
    <div class="b">
      <textarea readonly v-model="logInfo" v-show="logInfo">
      </textarea>
    </div>
  </div>
</template>
<script>
import apiCfgInfo from '@/api/cfgInfo'
export default {
  data() {
      return {
        startNum: 0,
        limitNum: 1000,
        rows: 30,
        logInfo: ''
      }
    },
    props: ['url'],
    methods: {
      getLogContent() {
        apiCfgInfo.getInfo(this.url, data => {
          if (data && data.log_content && data.log_content.length) {
            let logs = data.log_content
            this.startNum += logs.length
            this.logInfo = this.logInfo + logs.join("")
            setTimeout(() => {
              let container = document.getElementsByTagName("textarea")
              for (let i = 0; i < container.length; i++) {
                container[i].scrollTop = container[i].scrollHeight
              }
            }, 50)
          }
        }, {
          start: this.startNum,
          limit: this.limitNum
        })
      }
    },
    mounted() {
      this.getLogContent()
      this.timer = setInterval(() => {
        this.getLogContent()
      }, 5000)
    },
    destroyed() {
      window.clearInterval(this.timer)
    }

}
</script>
<style scoped>
textarea {
  position: absolute;
  top: 0;
  left: 0;
  width: 99%;
  bottom: 0;
}
</style>
