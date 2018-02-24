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
            <AttrRender :attr="attr" v-model="currentProject['order_info'][orderAttr.name][attr.name]" v-for="attr in orderAttr.attrs"></AttrRender>
          </div>
        </div>
      </div>
      <div v-show="currentPage==1">
        <div class="box" v-for="sampleAttr in sampleAttrs">
          <div class="h">
            <span>{{sampleAttr.name_cn}}</span>
          </div>
          <div class="b1">
            <AttrRender :attr="attr" v-model="currentProject['sample_info'][sampleAttr.name][attr.name]" v-for="attr in sampleAttr.attrs"></AttrRender>
          </div>
        </div>
      </div>
      <div v-show="currentPage==2">
        <table border style="width: 100%;border: 1px solid #ececec; border-collapse: collapse;text-align:left">
          <tbody>
            <tr>
              <th>序号</th>
              <th colspan="2">检测项目</th>
              <th>技术要求</th>
            </tr>
            <template v-for="(testItem,itemIndex) in testItems">
              <tr v-for="(param,paramIndex) in testItem.params">
                <td :rowspan="testItem.params.length" v-if="paramIndex == 0">{{itemIndex+1}}</td>
                <td :rowspan="testItem.params.length" v-if="paramIndex == 0" :colspan="testItem.params.length==1?2:1">
                  <input type="checkbox" :value="testItem.name" v-model="testInfo[testItem.name].checked">
                  <span>{{testItem.name_cn}}</span>
                </td>
                <td v-if="testItem.params.length !=1">
                  <span>{{param.name_cn}}</span>
                </td>
                <td>
                  <TplRender :attrs="param.attrs" :tpl="param.tpl" v-model="testInfo[testItem.name]['params'][param.name]"></TplRender>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
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
import TplRender from '../components/TplRender'

export default {
  name: 'PageOrderRec',
  data() {
    return {
      pages: ['基本信息', '试品主要技术参数', '试验项目及主要技术参数'],
      currentPage: 0,
      donePage: 1,
      currentProject: {},
      orderAttrs: [],
      sampleAttrs: [],
      testItems: [],
      testInfo: {},
    }
  },
  components: { AttrRender, TplRender },
  computed: {},
  mounted() {
    this.axios.get("/order-attrs").then(response => {
      let l_projectInfo = {
        order_info: {
          order_client: {},
          order_server: {},
          order_content: {}
        }
      }
      this.orderAttrs = response.data
      this.orderAttrs.map(orderAttr => {
        orderAttr.attrs.map(attr => {
          l_projectInfo["order_info"][orderAttr.name][attr.name] = attr.default_value ? attr.default_value : ''
        })
      })
      this.currentProject = mixObject(this.currentProject, l_projectInfo)
    }).catch(e => {
      this.$message({
        message: e['message'],
        type: 'error'
      })
    })
  },
  methods: {
    savePage() {
      if (this.currentPage == 0) {
        /*this.axios.post("/projects/order",JSON.stringify(this.currentProject)).then(response=>{

        })*/
        this.axios.get("/sample-attrs").then(response => {
          let l_projectInfo = {
            sample_info: {
              sample_base_info: {},
              sample_import_para: {},
              sample_main_para: {}
            }
          }
          this.sampleAttrs = response.data
          this.sampleAttrs.map(sampleAttr => {
            sampleAttr.attrs.map(attr => {
              l_projectInfo["sample_info"][sampleAttr.name][attr.name] = attr.default_value ? attr.default_value : ''
            })
          })
          this.currentProject = mixObject(this.currentProject, l_projectInfo)
          this.currentPage = 1
          this.donePage = 2
        })
      } else if (this.currentPage == 1) {
        this.axios.get("/test-items").then(response => {
          this.testItems = response.data
          this.currentPage = 2
          this.donePage = 3
          this.testItems.map(testItem => {
            this.$set(this.testInfo, testItem.name, {
              "checked": false,
              "params": {}
            })
            testItem.params.map(param => {
              this.testInfo[testItem.name]["params"][param.name] = {}
              param.attrs.map(attr => {
                this.testInfo[testItem.name]["params"][param.name][attr.name] = attr.default_value ? attr.default_value : ''
              })
            })
          })
        })
      }
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
