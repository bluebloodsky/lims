<template>
  <div>
    <h2>委托协议</h2>
    <div class="nav">
      <ul class="left-info">
        <li :class="{done:donePage>index,current:currentPage==index}" v-for="(page,index) in pages">
          <a @click="currentPage=index" v-if="donePage>index">{{page}}</a>
          <span v-else>{{page}}</span>
        </li>
      </ul>
      <div class="right-btn">
        <button>流程复制</button>
        <button v-if="currentPage == 2">下单</button>
        <button @click="savePage">保存</button>
      </div>
    </div>
    <div class="main-wrapper">
      <div v-show="currentPage==0">
        <div class="box" v-for="orderAttr in orderAttrs">
          <div class="h">
            <span>{{orderAttr.name_cn}}</span>
            <div class="right-btn" v-if="orderAttr.name == 'order_client'">
              <button type="text"><i class="iconfont icon-search"></i></button>
            </div>
          </div>
          <div class="b1">
            <AttrRender :attr="attr" v-model="currentProject['order'][orderAttr.name][attr.name]" v-for="attr in orderAttr.attrs"></AttrRender>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>
<script>
//import TempTestItemParam from './TempTestItemParam'
import {
  mapGetters,
  mapActions
} from 'vuex'

import { mixObject } from '@/shared/util'
import AttrRender from '../components/AttrRender'
var TempTestItemParam = {
  props: ['item_name', 'tpl'],
  created() {
    this.$options.template = this.tpl
  }
}

export default {
  name: 'PageOrderRec',
  data() {
    return {
      pages: ['基本信息', '试品主要技术参数', '试验项目及主要技术参数'],
      currentPage: 0,
      donePage: 0,
      currentProject: {},
      orderAttrs: [],
    }
  },
  components: { TempTestItemParam, AttrRender },
  computed: {},
  mounted() {
    let l_projectInfo = {
      order: {
        order_client: {},
        order_server: {},
        order_content: {}
      }
    }
    this.axios.get("/order-attrs").then(response => {
      this.orderAttrs = response.data
      this.orderAttrs.map(orderAttr => {
        orderAttr.attrs.map(attr => {
          l_projectInfo["order"][orderAttr.name][attr.name] = attr.defualt_value ? attr.defualt_value : ''
        })
      })
      this.currentProject = mixObject(this.currentProject, l_projectInfo)
    })
  },
  methods: {
    savePage(){
      
    }
  }
}

</script>
<style scoped>
.nav {
  margin: 0 15px;
  border: 1px solid #D4D4D4;
  height: 28px;
  line-height: 28px;
}

.box {
  width: 100%;
}

.left-info {
  float: left;
}

.left-info>li {
  float: left;
  width: 196px;
  text-align: center;
}

.left-info>li>a {
  width: 100%;
  height: 100%;
  display: block;
}

.done {
  background-image: url("../assets/ordernn.png");
  color: white;
}

.current {
  background-image: url("../assets/ordernh.png");
  color: white;
}

.right-btn {
  margin: 0px 3px;
  float: right;
}

.right-btn>button {
  width: 80px;
}

.main-wrapper {
  margin: 10px 15px;
  border: 1px solid #D4D4D4;
}

.form-code-item {
  position: relative;
  text-align: center;
  height: 60px;
  width: 95%;
  margin-left: 10px;
  border: #dfdfdf 1px solid;
}

.form-code-item>.form-code-name {
  line-height: 60px;
  width: 20%;
  display: inline-block;
  border-right: #dfdfdf 1px solid;
  float: left;
}

.form-code-item>.form-code-desc {
  line-height: 60px;
  width: 30%;
  display: inline-block;
  border-right: #dfdfdf 1px solid;
  float: left;
}

.form-code-item>.form-code-img {
  width: 45%;
  display: inline-block;
  border-right: #dfdfdf 1px solid;
  float: left;
}

.box-img {
  position: relative;
  display: inline-block;
  height: 50px;
  width: 100px;
  line-height: 50px;
  margin: 5px 10px;
  border: #dfdfdf 1px solid;
}

.box-img>img {
  max-width: 100%;
  max-height: 100%;
}

.box-img>button {
  position: absolute;
  font-size: 12px;
  right: -2px;
  top: 0px;
  color: red;
  text-align: center;
  border: none;
  background-color: transparent;
}

</style>
