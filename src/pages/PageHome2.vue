<template>
  <div>
    <div class="top">
      <img src="../assets/logo2.png">
      <h2>变压器故障-油中溶解气体仿真</h2>
      <!--
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
    -->
    </div>
    <div style="padding:10px;text-align:center">
      <el-button>低温过热（低于150℃）</el-button>
      <el-button>低温过热（150-300）℃</el-button>
      <el-button>中温过热（300-700）℃</el-button>
      <el-button>高温过热（高于700℃）</el-button>
      <el-button>局部放电</el-button>
      <el-button>低能放电兼过热</el-button>
      <el-button>低能放电</el-button>
      <el-button>电弧放电兼过热</el-button>
      <el-button>电弧放电</el-button>
    </div>
     <div class="b">
        <el-table :data="tbl_his_data" border style="width: 100%">
          <el-table-column :label="field.caption" align="center" :prop="field.name" v-for="field in tbl_fields">
          </el-table-column>
        </el-table>
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
      menus: [],
      tbl_his_data:[{
        H2:5.68 ,
        CH4:24.16,
        C2H4:19.12,
        C2H6:6.29,
        C2H2:0,
        CO:737,
        CO2:1870,
        totHy:49.57,
        type:'高温过热（高于700℃）'},{
        H2:6.62 ,
        CH4:26.9,
        C2H4:23.19,
        C2H6:7.94,
        C2H2:0,
        CO:777,
        CO2:2187,
        totHy:58.03,
        type:'中温过热（300-700）℃'},{
        H2:253 ,
        CH4:21.5,
        C2H4:16.1,
        C2H6:5.9,
        C2H2:72.4,
        CO:193,
        CO2:3546,
        totHy:115.9,
        type:'低能放电'},{
        H2:57.2 ,
        CH4:156.31,
        C2H4:349.78,
        C2H6:39.21,
        C2H2:1.69,
        CO:717,
        CO2:2734,
        totHy:546.99,
        type:'高温过热（高于700℃）'}],
      tbl_fields:[{
        name:"H2",
        caption:"氢气(ppm)"
      },{
        name:"CH4",
        caption:"甲烷(ppm)"
      },{
        name:"C2H4",
        caption:"乙烯(ppm)"
      },{
        name:"C2H6",
        caption:"乙烷(ppm)"
      },{
        name:"C2H2",
        caption:"乙炔(ppm)"
      },{
        name:"CO",
        caption:"一氧化碳(ppm)"
      },{
        name:"CO2",
        caption:"二氧化碳(ppm)"
      },{
        name:"totHy",
        caption:"总烃(ppm)"
      },{
        name:"type",
        caption:"故障类型"
      }]

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
    this.$taber.open({
      name: 'desktop'
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
