<template>
  <div>
    <div class="box top-box">
      <span>设备选择</span>
      <el-select v-model="choose_option.sen_id">
        <el-option :label="item.desc_cn" :value="item.sen_id" v-for="item in sensors"></el-option>
      </el-select>
      <span>开始时间</span>
      <el-date-picker v-model="choose_option.start_date" type="date" placeholder="选择日期">
      </el-date-picker>
      <span>截止时间</span>
      <el-date-picker v-model="choose_option.end_date" type="date" placeholder="选择日期">
      </el-date-picker>
      <el-button icon="search" @click="search"></el-button>
    </div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-chart">
        </i>
        <span>趋势图</span>
      </div>
      <div class="b" id="mychart">
      </div>
    </div>
    <div class="box right-box">
      <div class="h">
        <i class="mdi iconfont icon-history">
        </i>
        <span>历史详情</span>
      </div>
      <div class="b">{{pageSize}}
        <el-table :data="tableData5" border style="width: 100%">
          <el-table-column type="expand">
            <template scope="props">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item :label="field.caption" v-for="field in all_fields">
                  <span>{{ props.row[field.name] }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :label="field.caption" align="center" :prop="field.name" v-for="field in all_fields">
          </el-table-column>
        </el-table>
        <el-pagination :current-page.sync="currentPage" :page-sizes="[10, 20, 30, 50]" :page-size="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="pageTotal" style="width:100%">
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
        tableData5: [],
        chart: null,
        currentPage: 1,
        his_data: [],
        choose_option: {
          sen_id: '',
          start_date: '',
          end_date: ''
        },
        chartFields: [],
        all_fields: [{
          name: 'data_time',
          caption: '采集时间'
        }],
        pageSize:10,
        pageTotal:0
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
            desc_cn: item.desc_cn
          }
        })
      }
    },
    watch: {
      his_data: {
        handler(newValue) {

        }
      },
      deep: true
    },
    methods: {
      search() {
        checkData.
        checkData.getBeiJingaqi(data => {
          this.his_data = data
          this.chart = echarts.init(document.getElementById('mychart'))
          this.chart.showLoading()
          this.chart.setOption({
            title: {
              text: '1#主变油色谱'
            },
            tooltip: {
              trigger: 'axis'
            },
            xAxis: {
              data: data.map(function(item) {
                return item[0];
              })
            },
            yAxis: {
              splitLine: {
                show: false
              }
            },
            toolbox: {
              left: 'center',
              feature: {
                dataZoom: {
                  yAxisIndex: 'none'
                },
                restore: {},
                saveAsImage: {}
              }
            },
            dataZoom: [{
              startValue: '2014-06-01'
            }, {
              type: 'inside'
            }],
            visualMap: {
              top: 10,
              right: 10,
              pieces: [{
                gt: 0,
                lte: 300,
                color: '#096'
              }, {
                gt: 300,
                color: '#7e0023'
              }],
              outOfRange: {
                color: '#999'
              }
            },
            series: {
              name: 'Beijing AQI',
              type: 'line',
              data: data.map(function(item) {
                return item[1];
              }),
              markLine: {
                silent: true,
                data: [{
                  yAxis: 300
                }]
              }
            }
          })
          this.chart.hideLoading()
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
