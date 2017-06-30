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
            <a @click="logout">
              <i class="iconfont icon-exit"> 退出</i>
            </a>
          </li>
        </ul>
      </el-col>
    </el-row>
    <el-row style="margin:2px 0">
      <ZlMenu :datas="menuItems"></ZlMenu>
    </el-row>
    <router-view style="position:absolute; left:0;top:80px;bottom:20px; right:0"></router-view>
    <div class="footer">
      Copyright © 2017 国网电力科学研究院 武汉南瑞有限责任公司. All Rights Reserved
    </div>
  </div>
</template>
<script>
import ZlMenu from '@/components/Menu'
import {
  Loading
} from 'element-ui'

import apiBaseInfo from '@/api/baseInfo'
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
      menuItems: []
    }
  },
  components: {
    ZlMenu
  },
  mounted() {
    let loadingInstance = Loading.service({
      fullscreen: true
    })
    apiBaseInfo.getMenuInfo(menus => {
      this.menuItems = menus
      this.$store.dispatch('getMapParams', () => {
        loadingInstance.close()
        this.$store.dispatch('getDevices')
        this.$store.dispatch('getCurrentData')
        this.$store.dispatch('getDevStatus')
        this.$store.dispatch('getSysInfo')

      })
    })
    setInterval(() => {
      this.$store.dispatch('getCurrentData')
      this.$store.dispatch('getDevStatus')
      this.$store.dispatch('getSysInfo')
    }, 10000)
  },
  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push({
        path: '/login'
      })
    }
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
