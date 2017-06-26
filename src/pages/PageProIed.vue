<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-config-doc">
        </i>
        <span>采集参数配置</span>
        <div class="right-btn">
          <el-button type="text" @click="submitIedParams">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form label-width="200px" style="padding: 20px">
          <el-form-item v-for="item in iedParams" :label="item.desc_cn">
            <el-switch :width="switch_width" v-model="item.value" on-value="1" off-value="0" v-if="item.key=='autoRun' || item.key=='sendMsg'"></el-switch>
            <el-input v-model="item.value" v-else></el-input>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="box middle-box">
      <div class="h">
        <i class="mdi iconfont icon-debug">
        </i>
        <span>指令下达</span>
      </div>
      <div class="b">
        <el-form label-width="200px" label-position="top" style="padding: 20px 10px">
          <el-form-item v-for="item in issueInfo.fields" :label="item.caption">
            <el-select v-model="issueInfo.datas[item.name]" v-if="item.type == 'select'">
              <el-option :label="val" :value="key" v-for="(val,key) in select_options[item.sel_name]"></el-option>
            </el-select>
            <el-input v-model="issueInfo.datas[item.name]" :readonly="item.readonly" v-else></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="issue">下发</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="box right-box">
      <LogViewer url="/log_content"></LogViewer>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
import apiCfgInfo from '@/api/cfgInfo'
import LogViewer from '@/components/LogViewer'
export default {
  data() {
      return {
        switch_width: 80,
        iedParams: [],
        issueInfo: {
          fields: [{
            caption: '设备',
            name: 'sen_id',
            type: 'select',
            sel_name: 'sel_sensors'
          }, {
            caption: '指令',
            name: 'cmd',
            type: 'select',
            sel_name: 'sel_orders'
          }, {
            name: 'param',
            caption: '参数'
          }, {
            name: 'comment',
            caption: '参数格式',
            readonly: true
          }],
          datas: {
            sen_id: '',
            cmd: '',
            param: '',
            comment: ''
          }
        }
      }
    },
    computed: {
      ...mapGetters({
        map_params: 'map_params',
        devices: 'devices'
      }),
      select_options() {
        let sel_sensors = {}
        let sel_orders = {}
        this.devices.map(item => {
          sel_sensors[item.sen_id] = item.desc_cn
        })
        let l_sen_id = this.issueInfo.datas['sen_id']
        if (l_sen_id) {
          let l_sensor = this.devices.find(item => item.sen_id == l_sen_id)
          let l_orders = this.map_params.map_sen_debug.find(item => item.ln_class == l_sensor.ln_class && item.sen_type == l_sensor.sen_type)
          if (l_orders && l_orders.attrs) {
            l_orders.attrs.map(item => {
              sel_orders[item.cmd] = item.desc_cn
              if (this.issueInfo.datas['cmd'] == item.cmd) {
                this.issueInfo.datas['param'] = item.def_data
                this.issueInfo.datas['comment'] = item.comment
              }
            })

          }
        }
        return {
          sel_sensors: sel_sensors,
          sel_orders: sel_orders
        }
      }
    },
    components: {
      LogViewer
    },
    mounted() {
      apiCfgInfo.getCommuAttrs(data => {
        this.iedParams = data
      })
    },
    methods: {
      submitIedParams() {
        let obj = {}
        this.iedParams.map(item => {
          obj[item.key] = item.value
        })
        apiCfgInfo.postCommuAttrs(data => {
          this.$message({
            showClose: true,
            message: '更新采集参数配置成功',
            type: 'success'
          })
        }, obj)
      },
      issue() {
        let url = "/sensors/" + this.issueInfo.datas.sen_id + "/debug_orders"
        apiCfgInfo.postInfoByJson(url, {
          cmd: this.issueInfo.datas.cmd,
          param: this.issueInfo.datas.param
        }, data => {
          this.$message({
            showClose: true,
            message: data,
            type: 'success'
          })
        })
      }
    }
}
</script>
<style scoped>
.left-box {
  width: 30%;
  height: 100%;
}

.middle-box {
  width: 15%;
  height: 100%
}

.right-box {
  width: 55%;
  height: 100%
}
</style>
