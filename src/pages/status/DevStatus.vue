<template>
  <div>
    <div class="h">
      <i class="mdi iconfont icon-status">
        </i>
      <span>监测设备健康状态</span>
    </div>
    <div class="b">
      <div class="legend"><span>图例：</span>
        <i class="iconfont icon-ball good"></i><span>正常</span>
        <i class="iconfont icon-ball warn"></i><span>注意</span>
        <i class="iconfont icon-ball bad"></i><span>异常</span>
        <i class="iconfont icon-ball unkown"></i><span>不明</span>
      </div>
      <div ref="sc" class="sc">
        <el-row v-for="(ln,key) in lnDevices" v-if="map_params&&map_params['map_ln_class']">
          <el-col :span="3" class="left-desc">{{map_params['map_ln_class'][key]}}</el-col>
          <el-col :span="21" v-for="(line,index) in ln" :offset="index==0?0:3" class="right-status">
            <el-tooltip placement="right" effect="dark" v-for="device in line">
              <div slot="content">
                <ul v-if="device.data">
                  <li>
                    <span>监测时间</span>：<span>{{device.data.data_time}}</span>
                  </li>
                  <template v-if="device.data.data_attrs">
                    <li v-for="aData in device.data.data_attrs">
                      <span>{{aData.name}}</span>：
                      <template v-if="aData.value">
                        <span>{{aData.value}}</span> {{aData.unit}}
                      </template>
                      <span v-else>--</span>
                    </li>
                  </template>
                  <li v-else>
                    <span>数据无效</span>
                  </li>
                </ul>
                <span v-else>暂无数据</span>
              </div>
              <div class="device-box">
                <div>
                  <i class="iconfont icon-ball" :class="getClassStatus(device.status?device.status.mov_dev_conf:null)"></i>
                  <i class="iconfont icon-ball" :class="getClassStatus(device.status?device.status.sup_dev_run:null)"></i>
                  <i class="iconfont icon-ball" :class="getClassStatus(device.status?device.status.data_status:null)"></i>
                </div>
                <div>{{device['desc_cn']}}</div>
              </div>
            </el-tooltip>
          </el-col>
        </el-row>
      </div>
      <div class="bottom_desc">说明:监测设备三个状态值依次为：通讯状态、运行状态、监测数据状态</div>
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
        maxNum: 5
      }
    },
    mounted() {
      this.maxNum = parseInt(this.$refs.sc.clientWidth / 8 * 7 / 120)
      window.onresize = () => {
        if (this.$refs.sc) {
          this.maxNum = parseInt(this.$refs.sc.clientWidth / 8 * 7 / 120)
        }
      }
    },
    computed: {
      ...mapGetters({
        devices: 'devices',
        status: 'devStatus',
        currentData: 'currentData',
        map_params: 'map_params'
      }),
      lnDevices() {
        let rest = {}
        this.devices.map(item => {
          let senInfo = {
            sen_id: item.sen_id,
            desc_cn: item.desc_cn
          }
          senInfo.status = this.status.find(x => x['sen_id'] == item['sen_id'])


          let l_data = this.currentData.find(x => x['sen_id'] == item['sen_id'])
          l_data = (l_data && l_data.items && l_data.items[0]) ? l_data.items[0] : null
          if (senInfo.status && senInfo.status.data_time) {
            if (l_data && l_data.data_time == senInfo.status.data_time) {
              senInfo.data = l_data
            } else {
              senInfo.data = {
                data_time: senInfo.status.data_time
              }
            }
          }

          if (!rest[item.ln_class]) {
            rest[item.ln_class] = []
            rest[item.ln_class].push([senInfo])
          } else {
            let currentSize = rest[item.ln_class][rest[item.ln_class].length - 1].length
            if (currentSize < this.maxNum) {
              rest[item.ln_class][rest[item.ln_class].length - 1].push(senInfo)
            } else {
              rest[item.ln_class].push([senInfo])
            }
          }
        })
        return rest
      }
    },
    methods: {
      getClassStatus(statusVal) {
        return statusVal == 0 ? 'good' : (statusVal == 1 ? 'bad' : statusVal == 2 ? 'warn' : 'unkown')
      }
    }

}
</script>
<style scoped>
.legend {
  padding: 5px;
  text-align: right;
}

.legend > span {
  margin: 0 10px 0 2px;
}

.bottom_desc {
  margin-top: 10px;
  border-top: solid 1px #ccc;
}

.device-box {
  width: 100px;
  float: left;
  margin: 0 10px;
  text-align: center;
  cursor: pointer;
}

.sc {
  margin: 0 5px;
}

.left-desc,
.right-status {
  background: url(../../assets/stateBg.png) repeat-x;
  margin-bottom: 10px;
  text-align: left;
}

.left-desc {
  padding-left: 10px;
}

.device-box .iconfont {
  line-height: 23px;
}
</style>
