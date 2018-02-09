<template>
  <div>
    <div class="top">
      <img src="../assets/logo2.png">
      <h2>{{title}}</h2>
      <ZlMenu :datas="menuItems" style="margin-top:8px" :callback="openTab"></ZlMenu>
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
      pre_url: '/home/',
    }
  },
  watch: {
    '$route' (to, from) {
      let r = to.path.match(/\/home\/(\S+)/)
      if (r && r.length > 1) {
        let tab_url = r[1]
        this.menus.map(item => {
          if (item.url == tab_url) {
            this.$store.commit('addTab', item)
            return
          }
          if (item.items) {
            item.items.map(subitem => {
              if (subitem.url == tab_url) {
                this.$store.commit('addTab', subitem)
                return
              }
            })
          }
        })

      }
    }
  },
  computed: {
    ...mapGetters({
      user: 'user',
      tabs: 'tabs',
      currentTab: 'currentTab'
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
  components: {
    ZlMenu
  },
  mounted() {
    this.axios.get("/menus").then(response => {
      this.menus = response.data
    })
  },
  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push({
        path: '/login'
      })
    },
    openTab(obj) {
      this.$store.commit('addTab', obj)
      let tab_index = this.tabs.findIndex(item => {
        return item.url == obj.url
      })
      this.$router.push({ path: this.pre_url + obj.url })
    },
    closeTab(tab_index) {
      this.$store.commit('removeTab', tab_index)
      if (tab_index == this.currentTab) {
        this.$router.push({ path: this.pre_url + this.tabs[tab_index - 1].url })
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
