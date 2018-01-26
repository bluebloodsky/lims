<template>
  <div>
    <div class="box">
      <div class="h">
        <span>待办业务</span>
      </div>
      <div class="b">
        <el-table :data="taskTodos_list" border @row-click="todoClick">
          <el-table-column type="index" width="60">
          </el-table-column>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
          </el-table-column>
        </el-table>
      </div>      
      <a>更多>></a>
    </div>
    <div class="box">
      <div class="h">
        <span>我的日程</span>
      </div>
      <div class="b" id="mychart">
        <!--
       <full-calendar :events="fcEvents" :locale="de"></full-calendar>
     -->
      </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
import echarts from 'echarts'
export default {
  data() {
    return {
      fcEvents: [{
        title: '局放试验',
        start: '2017-08-25',
        end: '2017-08-27'
      }],
      fields: [{
        name: 'task_code',
        caption: '任务编号'
      }, {
        name: 'del_code',
        caption: '委托单编号'
      }, {
        name: 'task_type_cn',
        caption: '任务类型'
      }, {
        name: 'task_step_cn',
        caption: '当前环节'
      }],
      task_todo: [{
        id: 1,
        task_code: '21',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }, {
        id: 2,
        task_code: '51',
        del_code: '22',
        task_type: '1',
        task_step: '2'
      }, {
        id: 3,
        task_code: '31',
        del_code: '22',
        task_type: '1',
        task_step: '3'
      }, {
        id: 4,
        task_code: '41',
        del_code: '22',
        task_type: '1',
        task_step: '4'
      }, {
        id: 5,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '5'
      }, {
        id: 6,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '6'
      }, {
        id: 7,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '7'
      }, {
        id: 8,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '8'
      }, {
        id: 9,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '9'
      }, {
        id: 10,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }],
      task_dones: [{
        id: 1,
        task_code: '21',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }, {
        id: 2,
        task_code: '51',
        del_code: '22',
        task_type: '1',
        task_step: '2'
      }, {
        id: 3,
        task_code: '31',
        del_code: '22',
        task_type: '1',
        task_step: '3'
      }, {
        id: 4,
        task_code: '41',
        del_code: '22',
        task_type: '1',
        task_step: '4'
      }, {
        id: 5,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '5'
      }, {
        id: 6,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '6'
      }, {
        id: 7,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '7'
      }, {
        id: 8,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '8'
      }, {
        id: 9,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '9'
      }, {
        id: 10,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }]
    }
  },
  computed: {
    ...mapGetters({
      baseAttrs: 'baseAttrs',
    }),
    taskTodos_list() {
      return this.task_todo.map(item => {
        var item_list = JSON.parse(JSON.stringify(item))
        item_list.task_type_cn = this.baseAttrs.task_type[item.task_type]
        item_list.task_step_cn = this.baseAttrs.task_step[item.task_step]
        return item_list
      })
    },
    taskDones_list() {
      return this.task_dones.map(item => {
        var item_list = JSON.parse(JSON.stringify(item))
        item_list.task_type_cn = this.baseAttrs.task_type[item.task_type]
        item_list.task_step_cn = this.baseAttrs.task_step[item.task_step]
        return item_list
      })
    }
  },
  methods: {
    todoClick(row) {
      this.$router.push({
        path: '/home/pro_handle',
        params: {
          task: row
        }
      })
    }
  },
  mounted(){
    this.chart = echarts.init(document.getElementById('mychart'))
    var dateList = []

var heatmapData = [];
var lunarData = [];
for (var i = 0; i < dateList.length; i++) {
    heatmapData.push([
        dateList[i][0],
        Math.random() * 300
    ]);
    lunarData.push([
        dateList[i][0],
        1,
        dateList[i][1],
        dateList[i][2]
    ]);
}


var option = {
    tooltip: {
        formatter: function (params) {
            return '降雨量: ' + params.value[1].toFixed(2);
        }
    },

    visualMap: {
        show: false,
        min: 0,
        max: 300,
        calculable: true,
        seriesIndex: [2],
        orient: 'horizontal',
        left: 'center',
        bottom: 20,
        inRange: {
            color: ['#e0ffff', '#006edd'],
            opacity: 0.3
        },
        controller: {
            inRange: {
                opacity: 0.5
            }
        }
    },

    calendar: [{
        left: 'center',
        top: 'middle',
        cellSize: [80, 100],
        yearLabel: {show: false},
        orient: 'vertical',
        dayLabel: {
            firstDay: 1,
            nameMap: 'cn'
        },
        monthLabel: {
            show: true
        },
        range: '2017-03'
    }],

    series: [{
        type: 'scatter',
        coordinateSystem: 'calendar',
        symbolSize: 1,
        label: {
            normal: {
                show: true,
                formatter: function (params) {
                    var d = echarts.number.parseDate(params.value[0]);
                    return d.getDate() + '\n\n' + params.value[2] + '\n\n';
                },
                textStyle: {
                    color: '#000'
                }
            }
        },
        data: lunarData
    }, {
        type: 'scatter',
        coordinateSystem: 'calendar',
        symbolSize: 1,
        label: {
            normal: {
                show: true,
                formatter: function (params) {
                    return '\n\n\n' + (params.value[3] || '');
                },
                textStyle: {
                    fontSize: 14,
                    fontWeight: 700,
                    color: '#a00'
                }
            }
        },
        data: lunarData
    }, {
        name: '降雨量',
        type: 'heatmap',
        coordinateSystem: 'calendar',
        data: heatmapData
    }]
};
    this.chart.setOption(option)
  }
}

</script>
<style scoped>
.box {
  position: relative;
  width: 48%;
  height: 600px;
  margin: 1%;
  float: left;
  border: solid 1px #D4D4D4;
}

a {
  position: absolute;
  bottom: 10px;
  right: 10px;
}

</style>
