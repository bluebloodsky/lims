<template>
  <div>
    <div class="box top-box">
      <div class="h">
        <i class="mdi iconfont icon-config">
        </i>
        <span>监测参数配置</span>
        <div class="right-btn">
          <el-button type="text" @click="addMonitorParam"><i class="iconfont icon-plus"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-table :data="tblMonitorParam" border v-if="map_params">
          <el-table-column align="center" :filters="filters.filter_ln_class" :filter-method="filterLnClass" filter-placement="bottom-end" prop="ln_class_cn" label="监测类型">
          </el-table-column>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in monitorParamInfo.fields" v-if="!item.filter_flg">
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template scope="scope">
              <el-button @click.native.prevent="editMonitorParam(scope.row)" type="text"><i class="iconfont icon-edit"></i>
              </el-button>
              <el-button @click.native.prevent="delMonitorParam(scope.row)" type="text"><i class="iconfont icon-trash"></i>
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
        <span>I2参数配置</span>
        <div class="right-btn">
          <el-button type="text" @click="addI2Param"><i class="iconfont icon-plus"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-table :data="tblI2Param" border style="width: 100%" v-if="map_params">
          <el-table-column align="center" :filters="filters.filter_i2_group" :filter-method="filterI2Group" prop="group_name" label="接入数据类型">
          </el-table-column>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in i2ParamInfo.fields" v-if="!item.filter_flg">
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template scope="scope">
              <el-button @click.native.prevent="editI2Param(scope.row)" type="text"><i class="iconfont icon-edit"></i>
              </el-button>
              <el-button @click.native.prevent="delI2Param(scope.row)" type="text"><i class="iconfont icon-trash"></i>
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
          <el-form-item v-for="item in fields" :label="item.caption">
            <el-select v-model="currentDevice[item.real_name ? item.real_name : item.name]" v-if="item.sel_name">
              <el-option :label="val" :value="key" v-for="(val,key) in select_options[item.sel_name]"></el-option>
            </el-select>
            <el-input v-model="currentDevice[item.real_name ? item.real_name : item.name]" v-else></el-input>
          </el-form-item>
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
import apiBaseInfo from '@/api/baseInfo'
export default {
  data() {
      return {
        monitorParamInfo: {
          fields: [{
            name: 'ln_class_cn',
            real_name: 'ln_class',
            caption: '监测类型',
            sel_name: 'map_ln_class',
            filter_flg: true
          }, {
            name: 'do_name',
            caption: '参数名'
          }, {
            name: 'desc_cn',
            caption: '参数描述'
          }, {
            name: 'unit',
            caption: '单位'
          }, {
            name: 'ratio',
            caption: '变比'
          }, {
            name: 'import_level',
            caption: '重要等级'
          }, {
            name: 'precision',
            caption: '精度'
          }, {
            name: 'do_order',
            caption: '序号'
          }, {
            name: 'short_addr',
            caption: '短地址'
          }, {
            name: 'cdc_type_desc',
            real_name: 'cdc_type_id',
            caption: '参数类型',
            sel_name: 'map_cdc_type'
          }],
          datas: []
        },
        i2ParamInfo: {
          fields: [{
            name: 'group_name',
            real_name: 'group_id',
            caption: '接入数据类型',
            sel_name: 'map_i2_group',
            filter_flg: true
          }, {
            name: 'param_desc',
            real_name: 'param_name',
            caption: '参数名称',
            sel_name: 'map_monitor_param'
          }, {
            name: 'param_code',
            caption: '参数代码'
          }, {
            name: 'data_type_desc',
            real_name: 'data_type',
            caption: '字段类型',
            sel_name: 'map_data_type'
          }, {
            name: 'rate',
            caption: '变比'
          }, {
            name: 'param_alm_desc',
            real_name: 'param_alm_name',
            caption: '告警参数名',
            sel_name: 'map_monitor_param'
          }],
          datas: []
        },
        flg_showRightBox: false, //是否显示右边框
        flg_new: true, //是否为添加新设备
        right_title: '', //右侧标题栏
        currentDevice: {}, //右侧正在编辑的设备
        currentDeviceType: 0 //当前设备编辑设备类型，0为监测参数，1为I2参数
      }
    },
    computed: {
      ...mapGetters({
        map_params: 'map_params'
      }),
      tblMonitorParam() {
        return this.monitorParamInfo.datas.map(item => {
          let newItem = JSON.parse(JSON.stringify(item))
          newItem.ln_class_cn = this.map_params.map_ln_class[item.ln_class]
          return newItem
        })
      },
      fields() {
        return this.currentDeviceType == 0 ? this.monitorParamInfo.fields : this.i2ParamInfo.fields
      },
      filters() {
        let l_filter_ln_class = []
        for (let key in this.map_params.map_ln_class) {
          l_filter_ln_class.push({
            text: this.map_params.map_ln_class[key],
            value: this.map_params.map_ln_class[key]
          })
        }
        return {
          filter_ln_class: l_filter_ln_class,
          filter_i2_group: this.map_params.map_i2_group.map(item => {
            return {
              text: item.group_name,
              value: item.group_name
            }
          })
        }
      },
      tblI2Param() {
        return this.i2ParamInfo.datas.map(item => {
          let newItem = JSON.parse(JSON.stringify(item))
          let l_group = this.map_params.map_i2_group.find(adata => adata.group_id == item.group_id)
          newItem.group_name = l_group ? l_group.group_name : null
          let l_monitorParam = this.tblMonitorParam.find(adata => adata.do_name == item.param_name)
          newItem.param_desc = l_monitorParam ? l_monitorParam.desc_cn : null
          let l_data_type = this.map_params.map_i2_data_type.find(adata => adata.data_type_id == item.data_type)
          newItem.data_type_desc = l_data_type ? l_data_type.data_type_name : null
          let l_almMonitorParam = this.tblMonitorParam.find(adata => adata.do_name == item.param_alm_name)
          newItem.param_alm_desc = l_almMonitorParam ? l_almMonitorParam.desc_cn : null
          return newItem
        })
      },
      select_options() {
        let map_i2_group = {}
        this.map_params.map_i2_group.map(item => {
          map_i2_group[item.group_id] = item.group_name
        })

        let map_cdc_type = {}
        this.map_params.map_cdc_type.map(item => {
          map_cdc_type[item.cdc_type_id] = item.desc_cn
        })

        let map_data_type = {}
        this.map_params.map_i2_data_type.map(item => {
          map_data_type[item.data_type_id] = item.data_type_name
        })

        let map_monitor_param = {}
        if (this.currentDevice && this.currentDevice.group_id) {
          let i2_group = this.map_params.map_i2_group.find(item => item.group_id == this.currentDevice.group_id)
          console.log(i2_group)
          this.monitorParamInfo.datas.map(item => {
            if (item.ln_class == i2_group.ln_name) {
              map_monitor_param[item.do_name] = item.desc_cn
            }
          })
        }
        return {
          map_ln_class: this.map_params.map_ln_class,
          map_cdc_type: map_cdc_type,
          map_i2_group: map_i2_group,
          map_monitor_param: map_monitor_param,
          map_data_type: map_data_type
        }
      }
    },
    mounted() {
      apiBaseInfo.getMonitorParams(result => {
        this.monitorParamInfo.datas = result
      })
      apiBaseInfo.getI2Params(result => {
        this.i2ParamInfo.datas = result
      })
    },
    methods: {
      filterLnClass(value, row) {
        return row.ln_class_cn === value;
      },
      filterI2Group(value, row) {
        return row.group_name === value;
      },
      addMonitorParam() {
        this.flg_showRightBox = true
        this.currentDeviceType = 0
        this.flg_new = true
        this.right_title = '添加监测参数'
        let _currentDevice = {}
        this.monitorParamInfo.fields.map((item) => {
          _currentDevice[item.real_name ? item.real_name : item.name] = ''
        })
        this.currentDevice = _currentDevice
      },
      addI2Param() {
        this.flg_showRightBox = true
        this.currentDeviceType = 1
        this.flg_new = true
        this.right_title = '添加I2参数'
        let _currentDevice = {}
        this.i2ParamInfo.fields.map((item) => {
          _currentDevice[item.real_name ? item.real_name : item.name] = ''
        })
        this.currentDevice = _currentDevice
      },
      editMonitorParam(row) {
        this.flg_showRightBox = true
        this.currentDeviceType = 0
        this.flg_new = false
        this.right_title = '修改监测参数信息'
        this.currentDevice = JSON.parse(JSON.stringify(this.monitorParamInfo.datas.find(item => item.do_id == row.do_id)))
      },
      editI2Param(row) {
        this.flg_showRightBox = true
        this.currentDeviceType = 2
        this.flg_new = false
        this.right_title = '修改I2参数信息'
        this.currentDevice = JSON.parse(JSON.stringify(this.i2ParamInfo.datas.find(item => item.i2_paramgroup_id == row.i2_paramgroup_id)))
      },
      delMonitorParam(row) {
        apiBaseInfo.delMonitorParam(result => {
          this.monitorParamInfo.datas.map((item, index) => {
            if (item.do_id == row.do_id) {
              this.monitorParamInfo.datas.splice(index, 1)
            }
          })
        }, row.do_id)
      },
      delI2Param(row) {
        apiBaseInfo.delI2Param(result => {
          this.i2ParamInfo.datas.map((item, index) => {
            if (item.i2_paramgroup_id == row.i2_paramgroup_id) {
              this.i2ParamInfo.datas.splice(index, 1)
            }
          })
        }, row.i2_paramgroup_id)
      },
      hideDetail() {
        this.flg_showRightBox = false
      },
      submit() {
        if (this.flg_new) { //添加
          if (this.currentDeviceType == 0) { //添加
            apiBaseInfo.addMonitorParam((result) => {
              this.currentDevice.do_id = result.new_id
              this.monitorParamInfo.datas.push(this.currentDevice)
            }, this.currentDevice)
          } else { //添加
            apiBaseInfo.addI2Param((result) => {
              this.currentDevice.i2_paramgroup_id = result.new_id
              this.i2ParamInfo.datas.push(this.currentDevice)
            }, this.currentDevice)
          }
        } else { //修改
          if (this.currentDeviceType == 0) { //修改
            apiBaseInfo.modifyMonitorParam((result) => {
              let index = this.monitorParamInfo.datas.findIndex((val) =>
                val.do_id == this.currentDevice.do_id
              )
              this.monitorParamInfo.datas.splice(index, 1, this.currentDevice)
            }, this.currentDevice)
          } else {
            apiBaseInfo.modifyI2Param((result) => {
              let index = this.i2ParamInfo.datas.findIndex((val) =>
                val.i2_paramgroup_id == this.currentDevice.new_id
              )
              this.i2ParamInfo.datas.splice(index, 1, this.currentDevice)
            }, this.currentDevice)
          }
        }
        this.flg_showRightBox = false
      }
    }
}
</script>
<style scoped>
.top-box {
  width: 100%;
  height: 50%
}

.bottom-box {
  width: 100%;
  height: 50%
}
</style>
