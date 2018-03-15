<template>
  <div>
    <header>
      <div id="logo"><img src="../assets/logo2.png"></div>
      <h1>{{title}}</h1>
      <nav>
        <a class="menu-button"><img src="../assets/menu.png"></a>
        <PaddleMenu :datas="menuItems" :callback="openTab"></PaddleMenu>
      </nav>
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
    </header>
    <ul class="tabs">
      <li v-for="(tab,tab_index) in tabs" :key="tab.url">
        <router-link :to="pre_url+tab.url">{{tab.name}}
          <button class="destroy" @click.prevent="closeTab(tab_index)" v-if="tab_index!==0"><i class="iconfont icon-cancel"></i></button>
        </router-link>
      </li>
    </ul>
    <transition>
      <keep-alive :include="includedComponents">
        <router-view :closeCurrentTab="closeCurrentTab" style="position:absolute;top:88px;bottom:0;left:0;width:100%;"></router-view>
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
header {
  position: fixed;
  display: flex;
  width: 100%;
  height: 50px;
  justify-content: space-around;
  align-items: center;  
  color: white;
  background-color: #699;
  z-index: 999;
}

#logo {
  height: 50px;
  width: 110px;
  overflow: hidden;
}

#logo>img {
  height: 30px;
  margin: 10px 0;
}

header>h1 {
  color: #699;
  text-shadow: 1px 1px 1px #00000088, -1px -1px 1px #ffffff88;
  font-size: 26px;
}

nav {
  height: 32px;
}
.right-btns>li {
  float: left;
  margin-right: 20px;
}

.top a {
  color: white
}

ul.tabs {
  position: fixed;
  top: 52px;
  z-index: 99;
}

a.router-link-active {
  background-color: #fff;
  color: #666;
}

a.router-link-active:before {
  content: "";
  position: absolute;
  z-index: 0;
  left: 0;
  right: 0;
  top: -1px;
  background-color: #099;
  width: 100%;
  height: 2px;
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

.menu-button {
  display: none;
  height: 50px;
  width: 50px;
}

@media screen and (max-width: 1250px) {
  header>h1 {
    display: none;
  }
}

@media screen and (max-width: 1000px) {
  header>h1 {
    display: block;
  }
  nav {
    order: -1;
    width: 50px;
    height: 50px;
    background-color: #000;
  }
  nav>ul {
    display: none;
  }
  .menu-button {
    display: block;
  }
  nav:hover>ul {
    display: block;
    position: relative;
    z-index: 9999;
  }
}

@media screen and (max-width: 600px) {
  header>h1 {
    display: none;
  }
}

</style>
