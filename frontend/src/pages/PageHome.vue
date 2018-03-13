<template>
  <div>
    <div class="top">
      <a id="logo" href="#"><img src="../assets/logo2.png"></a>
      <h1>{{title}}</h1>
      <PaddleMenu :datas="menuItems" :callback="openTab"></PaddleMenu>
      <ul class="right-btns">
        <li>
          <a @click="logout">
            <i class="iconfont icon-account"> {{user.name_cn}} </i>
          </a>
        </li>
        <li>
          <a @click="logout">
              <i class="iconfont icon-exit"> 退出 </i>
            </a>
        </li>
      </ul>
    </div>
    <ul class="tabs">
      <li v-for="(tab,tab_index) in tabs" :key="tab.url">
        <router-link :to="pre_url+tab.url">{{tab.name}}
          <button class="destroy" @click.prevent="closeTab(tab_index)" v-if="tab_index!==0"><i class="iconfont icon-cancel"></i></button>
        </router-link>
      </li>
    </ul>
    <transition>
      <keep-alive :include="includedComponents">
        <router-view :closeCurrentTab="closeCurrentTab" style="position:absolute;top:90px;bottom:0;left:0;width:100%"></router-view>
      </keep-alive>
    </transition>
  </div>
</template>
<script>
import PaddleMenu from '@/components/PaddleMenu'
import apiBaseInfo from '@/api/baseInfo'
import {
  Loading
} from 'element-ui'
import {
  mapGetters
} from 'vuex'

export default {
  components: {
    PaddleMenu
  },
  props: {
    title: {
      type: String
    }
  },
  data() {
    return {
      menus: [],
      pre_url: '/home/',
    }
  },
  mounted() {
    this.axios.get("menus").then(response => {
      this.menus = response.data
    }).catch(err => {
      this.$message({
        message: err['error'],
        type: 'error'
      })
    })
    let r = this.$route.path.match(/\/home\/(\S+)/)
    if (r && r.length > 1) {
      let tab_url = r[1]
      this.$store.commit('addTab', tab_url)
    }
  },
  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push({
        path: '/login'
      })
    },
    openTab(url) {
      this.$store.commit('addTab', url)
      this.$router.push({ path: this.pre_url + url })
    },
    closeTab(tab_index) {
      if (this.$route.path == this.pre_url + this.tabs[tab_index].url) {
        this.$router.push({ path: this.pre_url + this.tabs[tab_index - 1].url })
      }
      this.$store.commit('removeTab', tab_index)
    },
    closeCurrentTab() {
      for (let tab_index = 0; tab_index < this.tabs.length; tab_index++) {
        if (this.$route.path == this.pre_url + this.tabs[tab_index].url) {
          this.$store.commit('removeTab', tab_index)
        }
      }
      this.$router.push({ path: this.pre_url + this.tabs[0].url })
    }
  },
  computed: {
    ...mapGetters({
      user: 'user',
      tabs: 'tabs',
    }),
    menuItems() {
      return this.menus
    },
    includedComponents() {
      return this.tabs.map(tab => {
        return tab.component
      }).join(',')
    }
  },


  destroyed() {
    //window.clearInterval(this.timer)
  }

}

</script>
<style scoped>
.top {
  position: relative;
  background-color: #699;
  color: white;
  padding: 0;
  margin: 0;
  width: 100%;
}

#logo {
  margin: 10px 10px;
  height: 30px;
  width: 110px;
  overflow: hidden;
  display: inline-block;
}

#logo>img {
  height: 30px;
}

.top>h1{
  display: inline-block;
  font-size: 26px;
  line-height: 50px;
  vertical-align: top;
  margin:0;
}
.top>ul{
  margin:0;
  display: inline-block;
  vertical-align: 50%;
}
.top>ul>li {
  float: left;
  margin-right: 20px;
}

ul.right-btns {
  position: absolute;
  top: 0;
  left: auto;
  right: 5px;
  bottom: auto;
  height: 50px;
  line-height: 50px;
}

.top a {
  color: white
}

a.router-link-active {
  background-color: #fff;  
  color: #666;
}

.destroy {
  margin: auto 0;
  border: none;
  padding: 0;
  background: transparent;
  color: #cc9a9a;
}

.destroy:hover {
  color: #af5b5e;
}

</style>
