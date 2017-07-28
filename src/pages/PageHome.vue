<template>
  <div>
    <div class="top">
      <img src="../assets/logo.png">
      <h2>{{title}}</h2>
      <ZlMenu :datas="menuItems" style="margin-top:8px"></ZlMenu>
      <ul class="right-btns">
        <li>
          <router-link to="/user">
            <i class="iconfont icon-account"> 管理員</i>
          </router-link>
        </li>
        <li>
          <a @click="logout">
              <i class="iconfont icon-exit"> 退出</i>
            </a>
        </li>
      </ul>
    </div>
    <div style="position:absolute; left:5px;top:55px;bottom:20px; right:0">
      {{menuItems}}
    </div>
    <div class="footer">
      Copyright © 2017 国网电力科学研究院 武汉南瑞有限责任公司. All Rights Reserved
    </div>
  </div>
</template>
<script>
import ZlMenu from '@/components/Menu'
import apiBaseInfo from '@/api/baseInfo'
import {
  Loading
} from 'element-ui'
import {
  mapGetters
} from 'vuex'

export default {
  props: {
    title: {
      type: String
    }
  },
  data() {
    return {
      menus: []
    }
  },
  computed: {
    ...mapGetters({
      user: 'user',
    }),
    menuItems() {
      return this.menus
    }
  },
  components: {
    ZlMenu
  },
  mounted() {
    apiBaseInfo.getMenuInfo(menus => {
      this.menus = menus
    })
  },
  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push({
        path: '/login'
      })
    }
  },
  destroyed() {
    //window.clearInterval(this.timer)
  }
}

</script>
<style scoped>
.top {
  background-color: #0097a7;
  color: white;
  line-height: 50px;
  height: 50px;
  padding: 0;
  margin: 0;
}

.top>img {
  float: left;
  margin: 3px 10px
}

.top h2 {
  margin: 0 10px;
  float: left;
}

.right-btns{
  float: right;
}
.top>ul>li {
  float: left;
  margin-right: 20px;
}
.top a{
  color: white
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
  background: #0097a7;
  font-size: 12px;
  color: #fff;
}

a {
  color: #01301E;
}

</style>
