<template>
  <div>
    <div class="top">
      <img src="../assets/logo2.png">
      <h2>{{title}}</h2>
      <ZlMenu :datas="menuItems" style="margin-top:8px" :callback="openTab"></ZlMenu>
      <ul class="right-btns">
        <li>
          <a @click="logout">
            <i class="iconfont icon-account"> 管理員</i>
          </a>
        </li>
        <li>
          <a @click="logout">
              <i class="iconfont icon-exit"> 退出</i>
            </a>
        </li>
      </ul>
    </div>
    <ul class="tabs">
      <li v-for="(tab,tab_index) in tabs">
        <router-link :to="pre_url+tab.url" @click.native="currentTab = tab_index">{{tab.name}}
          <button type="text" @click="closeTab(tab_index)" v-if="tab_index!==0">x</button>
        </router-link>
      </li>
    </ul>
    <transition>
      <keep-alive>
        <router-view></router-view>
      </keep-alive>
    </transition>
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
      menus: [],
      tabs: [{ url: 'desktop', name: '工作台' }],
      pre_url: '/home/',
      currentTab: 0
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
    this.$store.dispatch('getBaseAttrs')
    this.$store.dispatch('getClientAttrs')
    this.$store.dispatch('getServiceAttrs')
    this.$store.dispatch('getProtocolAttrs')
    this.$store.dispatch('getProductBaseAttrs')
    this.$store.dispatch('getProductImportAttrs')
    this.$store.dispatch('getProductCommonAttrs')
    this.$store.dispatch('getTestItemAttrs')
  },
  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push({
        path: '/login'
      })
    },
    openTab(obj) {
      let tab_index = this.tabs.findIndex(item => {
        return item.url == obj.url
      })
      if (tab_index == -1) {
        this.tabs.push(obj)
      }
      this.$router.push({ path: obj.url })
    },
    closeTab(tab_index) {
      this.tabs.splice(tab_index, 1)
      if (tab_index == this.currentTab) {
        this.$router.push({ path: this.pre_url + this.tabs[0].url })
      }
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
  margin: 10px 10px;
  height: 30px;
}

.top h2 {
  margin: 0 10px;
  float: left;
}

.right-btns {
  float: right;
}

.top>ul>li {
  float: left;
  margin-right: 20px;
}

.top a {
  color: white
}

a {
  color: #01301E;
}

.tabs {
  display: block;
  height: 32px;
  background-color: #D4D4D4;
}

.tabs>li {
  float: left;
  margin: 0 4px -1px 0;
  padding: 0;
}

.tabs a {
  padding: 5px;
  line-height: 33px;
}

.router-link-active {
  background-color: #fff;
}

</style>
