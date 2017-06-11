<template>
  <div style="height:100%">
    <div class="half-box">
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
    <div class="half-box">
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
    <div class="box">
      <div class="h">
        <i class="mdi iconfont icon-storage">
        </i>
        <span>存储状态</span>
      </div>
      <div class="b">
        <el-table :data="sysInfo.disk_status" border style="width: 100%">
          <el-table-column align="center" sortable :prop="item.name" :label="item.caption" v-for="item in disk_status_fields">
          </el-table-column>
        </el-table>
      </div>
    </div>
    <div class="box">
      <div class="h">
        <i class="mdi iconfont icon-process">
        </i>
        <span>服务状态</span>
      </div>
      <div class="b">
        <el-table :data="proStatus" border style="width: 100%">
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
.half-box,
.box {
  width: 100%;
  height: 35%;
  border-right: #dfdfdf 1px solid;
  border-bottom: #dfdfdf 1px solid;
  float: left;
  position: relative;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  /* Firefox */
  -webkit-box-sizing: border-box;
  /* Safari */
}

.half-box {
  width: 50%;
  height: 30%;
}

.half-box .el-row {
  text-align: center;
  padding: 5px 15px;
  font-size: 16px;
}

.half-box .el-row span {
  color: #259b24;
  font-weight: bold;
}

.el-form {
  font-size: 0;
  margin-left: 15px;
}

.el-form-item {
  margin-right: 0;
  margin-bottom: 0;
}

.el-form-item label {
  width: 90px;
  color: #fff;
}

.el-form-item span {
  font-weight: bold;
  color: #259b24;
  font-size: 20px;
}
</style>
