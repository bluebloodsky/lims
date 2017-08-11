<template>
  <div>
    <div class="top">
      <img src="../assets/logo2.png">
      <h2>{{title}}</h2>
      <ZlMenu :datas="menuItems" style="margin-top:8px"></ZlMenu>
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
    <vue-tabs></vue-tabs>
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
    this.$store.dispatch('getBaseAttrs')
    this.$store.dispatch('getClientAttrs')
    this.$store.dispatch('getServiceAttrs')
    this.$store.dispatch('getProtocolAttrs')
    this.$store.dispatch('getProductBaseAttrs')
    this.$store.dispatch('getProductImportAttrs')
    this.$store.dispatch('getTestItemAttrs')
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

</style>
