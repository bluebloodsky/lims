<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-config-doc">
        </i>
        <span>mms配置</span>
        <div class="right-btn">
          <el-button type="text" @click="UpdateMmsData">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="mmsInfo.datas" label-width="150px" style="padding: 20px">
          <el-form-item v-for="item in mmsInfo.fields" :label="item.caption">
            <el-select v-model="mmsInfo.datas[item.name]" v-if="item.type=='select'">
              <el-option :label="key" :value="key" v-for="key in select_options[item.sel_name]"></el-option>
            </el-select>
            <el-switch :width="switch_width" v-model="mmsInfo.datas[item.name]" on-value="1" off-value="0" v-else-if="item.type=='switch'"></el-switch>
            <el-input v-model="mmsInfo.datas[item.name]" :readonly="item.readonly" v-else></el-input>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-log">
        </i>
        <span>日志配置</span>
        <div class="right-btn">
          <el-button type="text" @click="UpdateLogData">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="logInfo.datas" label-width="150px" style="text-align:left;padding: 20px">
          <el-form-item v-for="item in logInfo.fields" :label="item.caption">
            <el-select v-model="logInfo.datas[item.name]" v-if="item.type=='select'">
              <el-option :label="key" :value="key" v-for="key in select_options[item.sel_name]"></el-option>
            </el-select>
            <el-switch :width="switch_width" v-model="logInfo.datas[item.name]" on-value="On" off-value="Off" v-else-if="item.type=='switch'"></el-switch>
            <el-input v-model="logInfo.datas[item.name]" v-else></el-input>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-debug">
        </i>
        <span>ntp配置</span>
        <div class="right-btn">
          <el-button type="text" @click="UpdateNTPData">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="ntpInfo.datas" label-width="150px" style="text-align:left;padding: 20px">
          <el-form-item v-for="item in ntpInfo.fields" :label="item.caption">
            <el-switch :width="switch_width" v-model="ntpInfo.datas[item.name]" on-value="1" off-value="0" v-if="item.type=='switch'"></el-switch>
            <el-input v-model="ntpInfo.datas[item.name]" v-else></el-input>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import apiCfgInfo from '@/api/cfgInfo'
export default {
  data() {
      return {
        switch_width: 80,
        iedInfo: [],
        mmsInfo: {
          fields: [{
            name: 'SclFile_IcdFileDir',
            caption: 'Icd文件目录(relative)',
            readonly: true
          }, {
            name: 'SclFile_IcdFileName',
            caption: 'Icd文件名',
            type: 'select',
            sel_name: 'icd_files'
          }, {
            name: 'SclFile_IedName',
            caption: '可用Ied名',
            readonly: true
          }, {
            name: 'SclFile_APName',
            caption: '可用AP名',
            type: 'select',
            sel_name: 'sel_ap'
          }, {
            name: 'MmsReport_ReportScanRate',
            caption: '日志扫描频率(ms)'
          }, {
            name: 'MmsReport_BRCBBufferSize',
            caption: '最大日志条目数'
          }, {
            name: 'MmsLog_SqliteFileName',
            caption: '日志数据库名'
          }, {
            name: 'MmsLog_SqliteMaxRows',
            caption: '日志数据库最大条目数'
          }, {
            name: 'MmsReport_ReportScanRate',
            caption: '报告扫描频率(ms)'
          }, {
            name: 'MmsReport_BRCBBufferSize',
            caption: '缓存报告块大小'
          }, {
            name: 'MmsFile_RootDir',
            caption: '文件服务目录(absolute)'
          }],
          datas: {}
        },
        logInfo: {
          fields: [{
            name: 'LogControl_LogElapsedTime',
            caption: '启用elapse时间标签',
            type: 'switch',
          }, {
            name: 'LogControl_LogFileEnable',
            caption: '启用日志文件',
            type: 'switch'
          }, {
            name: 'LogControl_LogFileSize',
            caption: '日志文件大小'
          }, {
            name: 'LogControl_LogBufferSize',
            caption: '日志缓存大小'
          }, {
            name: 'LogControl_LogDir',
            caption: '日志目录(absolute)'
          }, {
            name: 'LogControl_LogCnt',
            caption: '最大日志记录数'
          }, {
            name: 'LogControl_LogFileName',
            caption: '日志文件名'
          }, {
            name: 'LogControl_Setbuf',
            caption: '启用缓存',
            type: 'switch',
          }, {
            name: 'LogControl_Wrap',
            caption: '分割日志',
            type: 'switch',
          }],
          datas: []
        },
        ntpInfo: {
          fields: [{
            name: 'ntp_server_ip',
            caption: 'ntp服务器ip'
          }, {
            name: 'sync_cycle',
            caption: '对时周期(s)'
          }, {
            name: 'is_use_sharedMem',
            caption: '启用共享',
            type: 'switch'
          }, {
            name: 'out_time',
            caption: ' 网络超时时间(s)'
          }],
          datas: {
            ntp_server_ip: '',
            sync_cycle: '',
            is_use_sharedMem: '',
            out_time: ''
          }
        },
      }
    },
    mounted() {
      apiCfgInfo.getInfo('/mms_cfg', data => {
          this.iedInfo = data['IED_INFO']
          delete data['IED_INFO']
          this.mmsInfo.datas = data
        }),
        apiCfgInfo.getInfo('/log_cfg', data => {
          this.logInfo.datas = data
        })
      apiCfgInfo.getInfo('/ntp_info', data => {
        this.ntpInfo.datas = data

      })
    },
    computed: {
      select_options() {
        let icd_files = Object.keys(this.iedInfo)
        let current_icd_file_name = this.mmsInfo.datas['SclFile_IcdFileName']
        let aps = []
        if (this.iedInfo[current_icd_file_name]) {
          aps = this.iedInfo[current_icd_file_name].AP
          this.mmsInfo.datas.SclFile_IedName = this.iedInfo[current_icd_file_name].iedName
        }
        return {
          icd_files: icd_files,
          sel_ap: aps
        }
      }
    },
    methods: {
      UpdateMmsData() {
        apiCfgInfo.updateInfo('/mms_cfg', this.mmsInfo.datas, () => {
          this.$message({
            showClose: true,
            message: '更新mms配置信息成功',
            type: 'success'
          })
        });
      },
      UpdateLogData() {
        apiCfgInfo.updateInfo('/log_cfg', this.logInfo.datas);
      },
      UpdateNTPData() {
        apiCfgInfo.updateInfo('/ntp_info', this.ntpInfo.datas);
      }
    }
}
</script>
<style scoped>
.left-box {
  width: 33.33%;
  height: 100%;
}
</style>
