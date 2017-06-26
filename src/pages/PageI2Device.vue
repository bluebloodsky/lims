<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-config">
        </i>
        <span>CAC配置</span>
        <div class="right-btn">
          <el-button type="text" @click="updateCacInfo">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="cacInfo.datas" label-width="100px" style="text-align:left;padding: 20px">
          <el-form-item :label="item.caption" v-for="item in cacInfo.fields">
            <el-date-picker v-model="cacInfo.datas[item.name]" type="datetime" placeholder="选择日期时间" v-if="item.type == 'datetime'">
            </el-date-picker>
            <el-input v-model="cacInfo.datas[item.name]" v-else></el-input>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="box top-box">
      <div class="h">
        <i class="mdi iconfont icon-config">
        </i>
        <span>CAG配置</span>
        <div class="right-btn">
          <el-button type="text" @click="addCagInfo"><i class="iconfont icon-plus"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-table :data="cagInfo.datas" border style="width: 100%" v-if="map_params">
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in cagInfo.fields">
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template scope="scope">
              <el-button @click.native.prevent="editCagInfo(scope.$index)" type="text"><i class="iconfont icon-edit"></i>
              </el-button>
              <el-button @click.native.prevent="delCagInfo(scope.$index)" type="text"><i class="iconfont icon-trash"></i>
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
    <div class="box bottom-box">
      <div class="h">
        <i class="mdi iconfont icon-config">
        </i>
        <span>I2传感器配置</span>
        <div class="right-btn">
          <el-button type="text" @click="addSensorInfo"><i class="iconfont icon-plus"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-table :data="tblSensorData" border style="width: 100%" v-if="map_params">
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in sensorInfo.fields">
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template scope="scope">
              <el-button @click.native.prevent="editSensorInfo(scope.$index)" type="text"><i class="iconfont icon-edit"></i>
              </el-button>
              <el-button @click.native.prevent="delSensorInfo(scope.$index)" type="text"><i class="iconfont icon-trash"></i>
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
    <div class="right-pad-box" v-if="flg_showRightBox" style="left:70%">
      <div class="h">
        <span>{{right_title}}</span>
        <div class="right-btn">
          <el-button type="text" @click="submit"><i class="mdi iconfont icon-submit"></i></el-button>
          <el-button type="text" @click="hideDetail"><i class="iconfont icon-cancel"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="currentDevice" label-width="120px" style="text-align:left;padding: 20px">
          <template v-if="currentDeviceType == 0">
            <el-form-item v-for="item in cagInfo.fields" :label="item.caption">
              <el-input v-model="currentDevice[item.name]"></el-input>
            </el-form-item>
          </template>
          <template v-else-if="currentDeviceType == 1">
            <el-form-item v-for="item in sensorInfo.fields" :label="item.caption">
              <el-select v-model="currentDevice[item.real_name ? item.real_name : item.name]" v-if="item.sel_name">
                <el-option :label="val" :value="key" v-for="(val,key) in select_options[item.sel_name]"></el-option>
              </el-select>
              <el-input v-model="currentDevice[item.real_name ? item.real_name : item.name]" v-else></el-input>
            </el-form-item>
          </template>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
import apiI2Info from '@/api/i2Info'
export default {
  data() {
      return {
        cacInfo: {
          fields: [{
            name: 'cac_id',
            caption: 'CAC编码'
          }, {
            name: 'cac_ip',
            caption: 'IP地址'
          }, {
            name: 'heartbeat_period',
            caption: '心跳上传周期'
          }, {
            name: 'next_heartbeat_time',
            caption: '下次心跳时间',
            type: 'datetime'
          }],
          datas: {
            cac_id: '',
            cac_ip: '',
            heartbeat_period: '',
            next_heartbeat_time: ''
          }
        },
        cagInfo: {
          fields: [{
            name: 'cag_ip',
            caption: 'IP地址'
          }, {
            name: 'cag_port',
            caption: '端口号'
          }, {
            name: 'cag_service_locate',
            caption: '服务地址'
          }, {
            name: 'cag_namespace',
            caption: '命名空间'
          }, {
            name: 'timeout_time',
            caption: '超时时间'
          }],
          datas: []
        },
        sensorInfo: {
          fields: [{
            name: 'sensor_code',
            caption: '传感器编码'
          }, {
            name: 'group_name',
            caption: '组名',
            real_name: 'group_id',
            type: 'select',
            sel_name: 'map_i2_group'
          }, {
            name: 'ln_desc',
            caption: '传感器名称',
            real_name: 'ln_inst',
            type: 'select',
            sel_name: 'map_ln'
          }, {
            name: 'desc_cn',
            caption: 'i2设备描述'
          }, {
            name: 'equipment_id',
            caption: '一次设备编码'
          }, {
            name: 'dataupload_period',
            caption: '数据上传周期(min)'
          }, {
            name: 'next_dataupload_time',
            caption: '下次上传时间'
          }, {
            name: 'phase_name',
            caption: '相别',
            real_name: 'phase',
            type: 'select',
            sel_name: 'map_phase'
          }],
          datas: []
        },
        flg_showRightBox: false, //是否显示右边框
        flg_new: true, //是否为添加新设备
        right_title: '', //右侧标题栏
        currentDevice: {}, //右侧正在编辑的设备
        currentDeviceType: 0 //当前设备编辑设备类型，0为cag，1为sensor
      }
    },
    computed: {
      ...mapGetters({
        devices: 'devices',
        map_params: 'map_params'
      }),
      tblSensorData() {
        return this.sensorInfo.datas.map(item => {
          let newItem = JSON.parse(JSON.stringify(item))
          let l_group = this.map_params.map_i2_group.find(adata => adata.group_id == item.group_id)
          newItem.group_name = l_group.group_name
          newItem.phase_name = this.map_params.map_phase[item.phase]
          let l_device = this.devices.find(adata => adata.ln_inst == item.ln_inst && adata.ln_class == l_group.ln_name)
          newItem.ln_desc = l_device ? l_device.desc_cn : null
          return newItem
        })
      },
      cacData() {
        return {
          cac_id: this.cacInfo.datas.cac_id,
          cac_ip: this.cacInfo.datas.cac_ip,
          heartbeat_period: this.cacInfo.datas.heartbeat_period,
          next_heartbeat_time: new Date(this.cacInfo.datas.next_heartbeat_time).Format("yyyy-MM-dd hh:mm:ss")
        }
      },
      select_options() {
        let map_i2_group = {}
        this.map_params.map_i2_group.map(item => {
          map_i2_group[item.group_id] = item.group_name
        })

        let map_ln = {}
        if (this.currentDevice && this.currentDevice.group_id) {
          let i2_group = this.map_params.map_i2_group.find(item => item.group_id == this.currentDevice.group_id)
          this.devices.map(item => {
            if (item.ln_class == i2_group.ln_name) {
              map_ln[item.ln_inst] = item.desc_cn
            }
          })
        }
        return {
          map_i2_group: map_i2_group,
          map_phase: this.map_params.map_phase,
          map_ln: map_ln
        }
      }
    },
    mounted() {
      apiI2Info.getCacInfo(result => {
        if (result && result.length > 0) {
          this.cacInfo.datas = result[0]
        }
      })
      apiI2Info.getCagInfo(result => {
        this.cagInfo.datas = result
      })
      apiI2Info.getSensorInfo(result => {
        this.sensorInfo.datas = result
      })
    },
    methods: {
      updateCacInfo() {
        console.log(this.cacInfo.datas)
        apiI2Info.updateCacInfo(result => {
          this.$message({
            showClose: true,
            message: '更新cac信息成功',
            type: 'success'
          })
        }, this.cacData)
      },
      addCagInfo() {
        this.flg_showRightBox = true
        this.currentDeviceType = 0
        this.flg_new = true
        this.right_title = '添加cag设备'
        let _currentDevice = {}
        this.cagInfo.fields.map((item) => {
          _currentDevice[item.name] = ''
        })
        this.currentDevice = _currentDevice
      },
      addSensorInfo() {
        this.flg_showRightBox = true
        this.currentDeviceType = 1
        this.flg_new = true
        this.right_title = '添加传感器设备'
        let _currentDevice = {}
        this.sensorInfo.fields.map((item) => {
          _currentDevice[item.real_name ? item.real_name : item.name] = ''
        })
        this.currentDevice = _currentDevice
      },
      editSensorInfo(index) {
        this.flg_showRightBox = true
        this.currentDeviceType = 1
        this.flg_new = false
        this.right_title = '修改传感器信息'
        this.currentDevice = JSON.parse(JSON.stringify(this.sensorInfo.datas[index])) //简单粗暴的深度拷贝
      },
      editCagInfo(index) {
        this.flg_showRightBox = true
        this.currentDeviceType = 0
        this.flg_new = false
        this.right_title = '修改cag信息'
        this.currentDevice = JSON.parse(JSON.stringify(this.cagInfo.datas[index]))
      },
      delSensorInfo(index) {
        apiI2Info.delSensorInfo(result => {
          this.sensorInfo.datas.splice(index, 1)
        }, this.sensorInfo.datas[index].sensor_id)
      },
      delCagInfo(index) {
        apiI2Info.delCagInfo(result => {
          this.cagInfo.datas.splice(index, 1)
        }, this.cagInfo.datas[index].cag_id)
      },
      hideDetail() {
        this.flg_showRightBox = false
      },
      submit() {
        if (this.flg_new) { //添加
          if (this.currentDeviceType == 0) { //添加cag
            apiI2Info.addCagInfo((result) => {
              this.currentDevice.cag_id = result.new_id
              this.cagInfo.datas.push(this.currentDevice)
            }, this.currentDevice)
          } else { //添加sensor
            apiI2Info.addSensorInfo((result) => {
              this.currentDevice.sensor_id = result.new_id
              this.sensorInfo.datas.push(this.currentDevice)
            }, this.currentDevice)
          }
        } else { //修改
          if (this.currentDeviceType == 0) { //修改cag
            apiI2Info.modifyCagInfo((result) => {
              let index = this.cagInfo.datas.findIndex((val) =>
                val.cag_id == this.currentDevice.cag_id
              )
              this.cagInfo.datas.splice(index, 1, this.currentDevice)
            }, this.currentDevice)
          } else { //添加sensor
            apiI2Info.modifySensorInfo((result) => {
              let index = this.sensorInfo.datas.findIndex((val) =>
                val.sensor_id == this.currentDevice.sensor_id
              )
              this.sensorInfo.datas.splice(index, 1, this.currentDevice)
            }, this.currentDevice)
          }
        }
        this.flg_showRightBox = false
      }
    }
}
</script>
<style scoped>
.left-box {
  width: 25%;
  height: 100%;
}

.top-box {
  width: 75%;
  height: 30%
}

.bottom-box {
  width: 75%;
  height: 70%
}
</style>
