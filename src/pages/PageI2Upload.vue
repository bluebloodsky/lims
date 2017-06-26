<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-debug">
        </i>
        <span>指令下达</span>
      </div>
      <div class="b">
        <el-form label-width="200px" label-position="top" style="padding: 20px 10px">
          <el-form-item label="设备">
            <el-select v-model="issueInfo.sen_id">
              <el-option :label="item.desc_cn" :value="item.sensor_id" v-for="item in i2_sensors"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="指令">
            <el-select v-model="issueInfo.cmd">
              <el-option :label="val" :value="key" v-for="(val,key) in cmds"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="issue">下发</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="box right-box">
      <LogViewer url="/"></LogViewer>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
import apiI2Info from '@/api/i2Info'
import LogViewer from '@/components/LogViewer'
export default {
  data() {
      return {
        issueInfo: {
          sen_id: '',
          cmd: ''
        },
        i2_sensors: [],
        cmds: {
          1: '心跳上传',
          2: '数据上传',
          3: '配置上传'
        }
      }
    },
    computed: {
      ...mapGetters({
        map_params: 'map_params'
      })
    },
    mounted() {
      apiI2Info.getSensorInfo(result => {
        this.i2_sensors = result
      })
    },
    components: {
      LogViewer
    },
    methods:{
      issue(){
        
      }
    }
}
</script>
<style scoped>
.left-box {
  width: 20%;
  height: 100%
}

.right-box {
  width: 80%;
  height: 100%
}
</style>
