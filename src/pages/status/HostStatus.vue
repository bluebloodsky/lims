<template>
  <div>
    <div class="box half-box">
      <div class="h">
        <i class="mdi iconfont icon-host">
        </i>
        <span>主机状态</span>
      </div>
      <div class="b" v-if="sysInfo.host_status">
        <div v-for="item in host_status_fields" class="desc-item-box">
          <label>{{item.caption}}</label>
          <span> {{sysInfo.host_status[item.name]}}</span> {{item.unit}}
        </div>
      </div>
    </div>
    <div class="box half-box">
      <div class="h">
        <i class="mdi iconfont icon-memory">
        </i>
        <span>内存状态</span>
      </div>
      <div class="b" v-if="sysInfo.mem_status">
        <div v-for="item in mem_status_fields" class="desc-item-box">
          <label>{{item.caption}}</label>
          <span> {{sysInfo.mem_status[item.name]}}</span> {{item.unit}}
        </div>
      </div>
    </div>
    <div class="box bottom-box">
      <div class="h">
        <i class="mdi iconfont icon-storage">
        </i>
        <span>存储状态</span>
      </div>
      <div class="b">
        <el-table :data="sysInfo.disk_status" border>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in disk_status_fields">
          </el-table-column>
        </el-table>
      </div>
    </div>
    <div class="box bottom-box">
      <div class="h">
        <i class="mdi iconfont icon-process">
        </i>
        <span>服务状态</span>
      </div>
      <div class="b">
        <el-table :data="proStatus" border>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in pro_status_fields">
          </el-table-column>
        </el-table>
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
        host_status_fields: [{
          name: 'runTime',
          caption: '运行时间',
          unit: 'h'
        }, {
          name: 'freeTime',
          caption: '空闲时间',
          unit: 'h'
        }, {
          name: 'cpuNum',
          caption: 'CPU个数'
        }, {
          name: 'cpuFree',
          caption: 'CPU空闲比',
          unit: 'h'
        }],
        mem_status_fields: [{
          name: 'totalMem',
          caption: '总内存',
          unit: 'B'
        }, {
          name: 'freeMem',
          caption: '空闲内存',
          unit: 'B'
        }, {
          name: 'freePercent',
          caption: '内存空闲比',
          unit: '%'
        }],
        disk_status_fields: [{
          name: 'fileSystem',
          caption: '文件系统'
        }, {
          name: 'blocks',
          caption: '容量'
        }, {
          name: 'used',
          caption: '已用'
        }, {
          name: 'usePercent',
          caption: '已用(%)'
        }, {
          name: 'mountedOn',
          caption: '挂载点'
        }],
        pro_status_fields: [{
          name: 'pro_name',
          caption: '程序名'
        }, {
          name: 'run_status',
          caption: '运行状态'
        }, {
          name: 'run_time',
          caption: '运行时间'
        }, {
          name: 'pid',
          caption: 'pid'
        }]
      }
    },
    computed: mapGetters({
      sysInfo: 'sysInfo',
      proStatus: 'proStatus'
    })
}
</script>
<style scoped>
.half-box {
  width: 50%;
  height: 30%;
}

.bottom-box{
  width: 100%;
  height: 35%;
}
</style>
