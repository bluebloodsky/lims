<template>
  <div>
    <div class="h">
      <i class="mdi iconfont icon-config">
        </i>
      <span>监测设备管理</span>
      <div class="right-btn">
        <el-button type="text" @click="addRow"><i class="iconfont icon-plus"></i></el-button>
      </div>
    </div>
    <div class="b">
      <el-table :data="dev_table" border style="width: 100%" v-if="map_params">
        <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template scope="scope">
            <el-button @click.native.prevent="editRow(scope.$index)" type="text"><i class="iconfont icon-edit"></i>
            </el-button>
            <el-button @click.native.prevent="deleteRow(scope.$index)" type="text"><i class="iconfont icon-trash"></i>
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="right-pad-box" v-if="flg_showDetail" style="left:40%">
      <div class="h">
        <span>{{right_title}}</span>
        <div class="right-btn">
          <el-button type="text" @click="submit"><i class="mdi iconfont icon-submit"></i></el-button>
          <el-button type="text" @click="hideDetail"><i class="iconfont icon-cancel"></i></el-button>
        </div>
      </div>
      <div class="b">
        <div class="box half-box">
          <div class="h">
            <i class="mdi iconfont icon-base"></i>
            <span>基本信息</span>
          </div>
          <div class="b">
            <el-form :form="currentDevice" label-width="80px" style="text-align:left;padding: 20px">
              <el-form-item :label="item.caption" v-for="item in fields">
                <el-select v-model="currentDevice[item.real_name?item.real_name:item.name]" v-if="item.sel_name">
                  <el-option :label="val" :value="item.real_name?key:val" v-for="(val,key) in map_params[item.sel_name]"></el-option>
                </el-select>
                <el-input v-model="currentDevice[item.name]" v-else></el-input>
              </el-form-item>
            </el-form>
          </div>
        </div>
        <div class="box half-box">
          <div class="h">
            <i class="mdi iconfont icon-attribute"></i>
            <span>属性信息</span>
          </div>
          <div class="b">
            <el-form :form="currentAttrs" label-width="250px" style="text-align:left;padding: 20px" v-if="currentAttrs">
              <el-form-item v-for="(item,index) in currentAttrs" :label="item.desc_cn">
                <el-input v-model="item.value"></el-input>
              </el-form-item>
            </el-form>
          </div>
        </div>
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
        flg_showDetail: false,
        cur_index: -2,
        right_title: '',
        currentDevice: {},
        currentAttrs: [],
        fields: [{
          name: 'ln_class_cn',
          real_name: 'ln_class',
          sel_name: 'map_ln_class',
          caption: '监测类型'
        }, {
          name: 'sen_type',
          sel_name: 'map_sen_type',
          caption: '设备型号'
        }, {
          name: 'commu_type_cn',
          real_name: 'commu_type',
          sel_name: 'map_commu_type',
          caption: '通信类型'
        }, {
          name: 'desc_cn',
          caption: '设备描述'
        }, {
          name: 'ln_inst',
          caption: '设备号'
        }, {
          name: 's_addr',
          caption: '短地址'
        }]
      }
    },
    computed: {
      ...mapGetters({
        devices: 'devices',
        map_params: 'map_params'
      }),
      dev_table() {
        return this.devices.map(item => {
          let l_item = JSON.parse(JSON.stringify(item)) //简单粗暴的深度拷贝
          l_item.ln_class_cn = this.map_params['map_ln_class'][item.ln_class]
          l_item.commu_type_cn = this.map_params['map_commu_type'][item.commu_type]
          return l_item
        })
      }
    },
    watch: {
      cur_index(newIndex) {
        let l_flg = newIndex != -1
        this.currentDevice = {
          sen_id: l_flg ? this.devices[newIndex].sen_id : null,
          ln_class: l_flg ? this.devices[newIndex].ln_class : null,
          sen_type: l_flg ? this.devices[newIndex].sen_type : null,
          commu_type: l_flg ? this.devices[newIndex].commu_type : null,
          desc_cn: l_flg ? this.devices[newIndex].desc_cn : null,
          ln_inst: l_flg ? this.devices[newIndex].ln_inst : null,
          s_addr: l_flg ? this.devices[newIndex].s_addr : null,
        }
      },
      currentDevice: {
        handler(newValue) {
          let l_sen_attr = newValue.ln_class && newValue.sen_type ?
            this.map_params.map_sen_attr.find(x => x['sen_type'] == newValue.sen_type && x['ln_class'] == newValue.ln_class) : []
          l_sen_attr = l_sen_attr && l_sen_attr.attrs ? l_sen_attr.attrs : []
          l_sen_attr.map(item => {
            let l_attr = this.cur_index != -1 ? this.devices[this.cur_index].attrs.find(x => x['attr'] == item['attr']) : null
            item.value = l_attr && l_attr.value ? l_attr.value : item.attr_def_val
          })
          this.currentAttrs = l_sen_attr
        },
        deep: true
      }
    },
    methods: {
      addRow() {
        this.flg_showDetail = true
        this.right_title = '新增信息'
        this.cur_index = -1
      },
      hideDetail() {
        this.cur_index = -1
        this.flg_showDetail = false
      },
      editRow(index) {
        this.flg_showDetail = true
        this.right_title = '详细信息'
        this.cur_index = index
      },
      deleteRow(index) {
        this.$store.dispatch('delDevice', this.devices[index].sen_id);
      },
      submit() {
        let new_device = {
          sen_id: this.currentDevice.sen_id,
          ln_class: this.currentDevice.ln_class,
          sen_type: this.currentDevice.sen_type,
          commu_type: this.currentDevice.commu_type,
          desc_cn: this.currentDevice.desc_cn,
          ln_inst: this.currentDevice.ln_inst,
          s_addr: this.currentDevice.s_addr,
          attrs: []
        }
        this.currentAttrs.map(item => {
          new_device.attrs.push({
            "attr": item.attr,
            value: item.value
          })
        })
        if (this.cur_index == -1) { //添加
          this.$store.dispatch('addDevice', new_device)
        } else { //修改
          this.$store.dispatch('modifyDevice', new_device)
        }
        this.flg_showDetail = false
      }
    }
}
</script>
<style scoped>
.half-box {
  width: 50%;
  height: 100%;
}
</style>
