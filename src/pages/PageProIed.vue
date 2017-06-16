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
        <el-form :form="iedParams" label-width="200px" style="text-align:left;padding: 20px">
          <el-form-item v-for="item in iedParams" :label="item.desc_cn">
            <el-switch :width="switch_width" v-model="item.value" on-value="1" off-value="0" v-if="item.key=='autoRun' || item.key=='sendMsg'"></el-switch>
            <el-input v-model="item.value" v-else></el-input>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="box top-box">
      <div class="h">
        <i class="mdi iconfont icon-debug">
        </i>
        <span>指令下达</span>
      </div>
      <div class="b">
        <template v-for="item in issueInfo.fields">
          <span>{{item.caption}}</span>
          <el-select v-model="currentIssue[item.real_name]" v-if="item.type == 'select'">
            <el-option :label="val" :value="key" v-for="(val,key) in select_options[item.sel_name]"></el-option>
          </el-select>
          <el-input v-model="currentIssue[item.name]" v-else></el-input>
        </template>
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
import apiCfgInfo from '@/api/cfgInfo'
export default {
  data() {
      return {
        switch_width: 80,
        iedParams: [],
        currentIssue: {},
        issueInfo: {
          fields: [{
            name: 'desc_cn',
            caption: '设备',
            real_name: 'sen_id',
            type: 'select',
            sel_name: ''
          }, {
            name: 'order',
            caption: '指令',
            real_name: 'order_id',
            type: 'select',
            sel_name: 'sel_orders'
          }, {
            name: 'param',
            caption: '参数',
          }, {
            name: 'desc',
            caption: '描述'
          }],
          datas: {
            sen_id: '',
            order_id: '',
            param: '',
            desc: ''
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

      }
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
      }
    }
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
