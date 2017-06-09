<template>
  <div>
    <el-row class="top">
      <el-col :span="8" style="text-align:left;padding-left:5px">
        <router-link to="/"><img src="../assets/logo.png"></router-link>
      </el-col>
      <el-col :span="8">
        <h1 style="display:inline">{{title}}</h1>
      </el-col>
      <el-col :span="8">
        <ul>
          <li>
            <router-link to="/user">
              <i class="iconfont icon-account"> {{userName}}</i>
            </router-link>
          </li>
          <li>
            <router-link to="/message">
              <i class="iconfont icon-message"> 消息</i>
            </router-link>
          </li>
          <li>
            <router-link to="/help">
              <i class="iconfont icon-help"> 帮助</i>
            </router-link>
          </li>
          <li>
            <router-link to="/">
              <i class="iconfont icon-exit"> 退出</i>
            </router-link>
          </li>
        </ul>
      </el-col>
    </el-row>
    <el-row style="margin:2px 0">
      <ZlMenu :datas="menuItems"></ZlMenu>
    </el-row>
    <router-view style="position:absolute; left:0;top:80px;bottom:20px; right:0"></router-view>
    <div class="footer">
      Copyright © 2016 国网电力科学研究院 武汉南瑞有限责任公司. All Rights Reserved
    </div>
  </div>
</template>
<script>
import ZlMenu from '@/components/Menu'
import {
  Loading
} from 'element-ui'
export default {
  props: {
    title: {
      type: String
    },
    userName: {
      type: String,
      default: 'admin'
    }
  },
  data() {
    return {
      menuItems: [{
        name: '状态总览',
        url: '/status'
      }, {
        name: '数据查看',
        items: [{
          name: '实时数据',
          url: '/real_data'
        }, {
          name: '历史数据',
          url: '/his_data'
        }]
      }, {
        name: '报警记录',
        url: '/alarm'
      }, {
        name: '配置总览',
        items: [{
          name: '监测设备管理',
          url: '/monitor_device'
        }, {
          name: 'I2台帐录入',
          url: '/i2_device'
        }, {
          name: '监测参数配置',
          url: '/monitor_param'
        }, {
          name: 'I2参数配置',
          url: '/i2_param'
        }, {
          name: 'mms配置',
          url: '/mms_config'
        }, {
          name: 'ied参数配置',
          url: '/ed_config'
        }, {
          name: '日志配置',
          url: '/log_config'
        }, {
          name: 'ntp配置',
          url: '/ntp_config'
        }]
      }, {
        name: '程序调试',
        items: [{
          name: '采集程序调试',
          url: '/pro_ied'
        }, {
          name: 'I2上传调试',
          url: '/i2_upload'
        }, {
          name: '文件管理',
          url: '/file_manager'
        }]
      }]
    }
  },
  components: {
    ZlMenu
  },
  beforeCreate() {

  },
  mounted() {
    let loadingInstance = Loading.service({
      fullscreen: true
    })
    this.$store.dispatch('getMapParams', () => {
      this.$store.dispatch('getDevices')
      this.$store.dispatch('getCurrentData')
      this.$store.dispatch('getDevStatus')
      this.$store.dispatch('getSysInfo')
      loadingInstance.close()
    })
    setInterval(() => {
      this.$store.dispatch('getCurrentData')
      this.$store.dispatch('getDevStatus')
      this.$store.dispatch('getSysInfo')
    }, 5000)
  }
}
</script>
<style scoped>
.top {
  background-color: #d1e3ef;
  color: #01301E;
  line-height: 45px;
  height: 45px;
  padding: 0;
  margin: 0;
  overflow: hidden;
}

.el-col > ul {
  list-style: none;
  overflow: hidden;
  margin-top: 5px;
  height: 40px;
  line-height: 40px;
  float: right;
}

.el-col > ul>li {
  float: left;
  margin-right: 20px;
}

.footer {
  position: fixed;
  bottom: 0;
  margin: 0;
  height: 20px;
  line-height: 20px;
  right: 0;
  left: 0;
  text-align: center;
  background: #5F8BA4;
  font-size: 12px;
  color: #fff;
}

a {
  color: #01301E;
}
</style>
