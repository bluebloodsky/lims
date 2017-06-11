<template>
  <div>
    <div class="box top-box">
      <span>设备选择</span>
      <el-select v-model="choose_option.sen_id">
        <el-option :label="item.desc_cn" :value="item.sen_id" v-for="item in sensors"></el-option>
      </el-select>
      <span>日期范围</span>
      <el-date-picker v-model="choose_option.daterange" type="daterange" placeholder="选择日期范围">
      </el-date-picker>
      <el-button icon="search" @click="search"></el-button>
    </div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-chart">
        </i>
        <span>趋势图</span>
      </div>
      <div class="b" id="mychart"> </div>
    </div>
    <div class="box right-box">
      <div class="h">
        <i class="mdi iconfont icon-history">
        </i>
        <span>历史详情</span>
      </div>
      <div class="b">
        <el-table :data="tbl_his_data" border style="width: 100%">
          <el-table-column type="expand">
            <template scope="props">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item :label="field.caption" v-for="field in all_fields">
                  <span>{{ props.row[field.name] }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :label="field.caption" align="center" :prop="field.name" v-for="field in tbl_fields">
          </el-table-column>
        </el-table>
        <el-pagination :current-page.sync="currentPage" :page-sizes="[5, 10, 20, 30, 50]" :page-size="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total" @size-change="pageSizeChange" style="width:100%">
        </el-pagination>
      </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'

import checkData from '../api/checkData'
import echarts from 'echarts'
export default {
  data() {
      return {
        his_data: [],
        chart: null,
        choose_option: {
          sen_id: '',
          daterange: []
        },
        cur_ln_class: '',
        chart_fields: [],
        all_fields: [],
        tbl_fields: [{
          name: 'data_time',
          caption: '采集时间'
        }],
        do_attrs: [],
        pageSize: 10,
        total: 0,
        currentPage: 1
      }
    },
    computed: {
      ...mapGetters({
        devices: 'devices',
        map_params: 'map_params'
      }),
      sensors() {
        return this.devices.map(item => {
          return {
            sen_id: item.sen_id,
            ln_class: item.ln_class,
            desc_cn: item.desc_cn
          }
        })
      },
      tbl_his_data() {
        return this.his_data.slice((this.currentPage - 1) * this.pageSize, this.currentPage * this.pageSize)
      }
    },
    watch: {
      cur_ln_class(newValue) {
        this.chart_fields = []
        this.tbl_fields = [{
          name: 'data_time',
          caption: '采集时间'
        }]
        this.all_fields = [{
          name: 'data_time',
          caption: '采集时间'
        }]
        this.do_attrs.map((item) => {
          if (item.ln_class == newValue) {
            if (item.import_level > 1) {
              this.tbl_fields.push({
                name: item.desc_cn,
                caption: item.desc_cn + (item.unit ? '(' + item.unit + ')' : '')
              })
              this.chart_fields.push(item.desc_cn)
            }
            if (item.import_level > 0) {
              this.all_fields.push({
                name: item.desc_cn,
                caption: item.desc_cn + (item.unit ? '(' + item.unit + ')' : '')
              })
            }

          }
        })
      }
    },
    mounted() {
      checkData.getDoAttrs(data => {
        this.do_attrs = data
      }, {
        fields: 'ln_class,desc_cn,import_level,unit'
      })
      this.chart = echarts.init(document.getElementById('mychart'))
      window.onresize = () => {
        this.chart.resize()
      }
    },
    methods: {
      pageSizeChange(newValue) {
        this.pageSize = newValue
      },
      search() {
        this.cur_ln_class = this.sensors.find(item => item.sen_id == this.choose_option.sen_id).ln_class
        this.chart.showLoading()
        checkData.getHisData(data => {
          this.his_data = []
          let xAxis = []
          let series = []
          this.chart_fields.map(item => {
            series.push({
              name: item,
              data: [],
              type: 'line'
            })
          })
          data.items.map(item => {
            let his_data_item = {
              data_time: item.data_time
            }
            item.data_attrs.map(attr => {
              his_data_item[attr.name] = attr.value
              let se = series.find(series_item => series_item.name == attr.name)
              if (se) {
                se.data.push(attr.value)
              }
            })
            this.his_data.push(his_data_item)
            xAxis.push(item.data_time)
          })

          this.total = this.his_data.length
            /*绘制图表*/
          this.chart.setOption({
            legend: {
              data: this.chart_fields
            },
            tooltip: {
              trigger: 'axis'
            },
            xAxis: {
              data: xAxis
            },
            yAxis: {
              splitLine: {
                show: false
              }
            },
            dataZoom: [{
              type: 'slider',
              start: 80,
              end: 100
            }, {
              type: 'inside',
              start: 80,
              end: 100
            }],
            series: series
          })
          this.chart.hideLoading()
        }, this.choose_option.sen_id, {
          time_min: this.choose_option.daterange[0] ? this.choose_option.daterange[0].Format("yyyyMMddhhmmss") : '00000000000000',
          time_max: this.choose_option.daterange[1] ? this.choose_option.daterange[1].Format("yyyyMMddhhmmss") : '99999999999999'
        })
      }
    }
}
</script>
<style scoped>
.box {
  border-right: #dfdfdf 1px solid;
  border-bottom: #dfdfdf 1px solid;
  position: absolute;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  /* Firefox */
  -webkit-box-sizing: border-box;
  /* Safari */
}

.top-box {
  left: 0;
  right: 0;
  top: 10px;
  height: 50px;
  text-align: center;
}

.left-box {
  left: 0;
  right: 50%;
  top: 60px;
  bottom: 0;
}

.right-box {
  left: 50%;
  right: 0;
  top: 60px;
  bottom: 0;
}

.demo-table-expand {
  font-size: 0;
}

.demo-table-expand label {
  width: 90px;
  color: #99a9bf;
}

.demo-table-expand .el-form-item {
  margin-right: 0;
  margin-bottom: 0;
  width: 50%;
}
</style>
