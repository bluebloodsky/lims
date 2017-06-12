<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <i class="mdi iconfont icon-config-doc">
        </i>
        <span>mms配置</span>
        <div class="right-btn">
          <el-button type="text" @click="">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="mmsInfo.datas" label-width="150px" style="padding: 20px">
          <el-form-item v-for="item in mmsInfo.fields" :label="item.caption">
            <el-select v-model="mmsInfo.datas[item.real_name ? item.real_name : item.name]" v-if="item.sel_name">
              <el-option :label="val" :value="key" v-for="(val,key) in select_options[item.sel_name]"></el-option>
            </el-select>
            <el-input v-model="mmsInfo.datas[item.name]" v-else></el-input>
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
          <el-button type="text" @click="">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="logInfo.datas" label-width="150px" style="text-align:left;padding: 20px">
          <el-form-item v-for="item in logInfo.fields" :label="item.caption">
            <el-select v-model="logInfo.datas[item.name]" v-if="item.sel_name">
              <el-option :label="val" :value="key" v-for="(val,key) in select_options[item.sel_name]"></el-option>
            </el-select>
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
          <el-button type="text" @click="">
            <i class="iconfont icon-submit"></i>
          </el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="ntpInfo.datas" label-width="150px" style="text-align:left;padding: 20px">
          <el-form-item v-for="item in ntpInfo.fields" :label="item.caption">
            <el-switch on-color="#13ce66" off-color="#ff4949" width="80" v-model="ntpInfo.datas[item.name]" on-value="1" off-value="0" v-if="item.flg"></el-switch>
            <el-input v-model="ntpInfo.datas[item.name]" v-else></el-input>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      mmsInfo: {
        fields: [{
          name: 'file_name',
          caption: 'Icd文件目录(relative)'
        }, {
          name: 'size',
          caption: 'Icd文件名'
        }, {
          name: 'update_time',
          caption: '可用Ied名'
        }, {
          name: 'update_time',
          caption: '可用AP名'
        }, {
          name: 'file_name',
          caption: '日志扫描频率(ms)'
        }, {
          name: 'size',
          caption: '最大日志条目数'
        }, {
          name: 'update_time',
          caption: '日志数据库名'
        }, {
          name: 'update_time',
          caption: '日志数据库最大条目数'
        }, {
          name: 'file_name',
          caption: '报告扫描频率(ms)'
        }, {
          name: 'size',
          caption: '缓存报告块大小'
        }, {
          name: 'file_name',
          caption: '文件服务目录(absolute)'
        }],
        datas: {}
      },
      logInfo: {
        fields: [{
          name: 'file_name',
          caption: '启用elapse时间标签'
        }, {
          name: 'size',
          caption: '启用日志文件'
        }, {
          name: 'update_time',
          caption: '日志文件大小'
        }, {
          name: 'file_name',
          caption: '日志缓存大小'
        }, {
          name: 'size',
          caption: '日志目录(absolute)'
        }, {
          name: 'update_time',
          caption: '最大日志记录数'
        }, {
          name: 'size',
          caption: '日志文件名'
        }, {
          name: 'update_time',
          caption: '启用缓存'
        }, {
          name: 'update_time',
          caption: '分割日志'
        }],
        datas: []
      },
      ntpInfo: {
        fields: [{
          name: 'ip',
          caption: 'ntp服务器ip'
        }, {
          name: 'period',
          caption: '对时周期(s)'
        }, {
          name: 'flg_share',
          caption: '启用共享',
          flg: true
        }, {
          name: 'timout',
          caption: ' 网络超时时间(s)'
        }],
        datas: {
          ip: '',
          period: '',
          flg_share: '',
          timout: ''
        }
      },
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
