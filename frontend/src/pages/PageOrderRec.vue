<template>
  <div>
    <h2>委托协议</h2>
    <nav>
      <ul class="left-info">
        <li :class="{done:donePage>index,current:currentPage==index}" v-for="(page,index) in pages">
          <a @click="currentPage=index" v-if="donePage>index">{{page}}</a>
          <span v-else>{{page}}</span>
        </li>
      </ul>
      <div class="right-operate">
        <button type="text">流程复制</button>
        <button type="text" v-if="currentPage == 2 && !currentProject.doneSteps" @click="submitProject">下单</button>
        <button type="text" @click="savePage">保存</button>
      </div>
    </nav>
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
            <AttrRender :attr="attr" v-model="orderInfo[orderAttr.name][attr.name]" v-for="attr in orderAttr.attrs"></AttrRender>
          </div>
        </div>
      </div>
      <div v-show="currentPage==1">
        <div class="box" v-for="sampleAttr in sampleAttrs">
          <div class="h">
            <span>{{sampleAttr.name_cn}}</span>
          </div>
          <div class="b1">
            <AttrRender :attr="attr" v-model="sampleInfo[sampleAttr.name][attr.name]" v-for="attr in sampleAttr.attrs"></AttrRender>
          </div>
        </div>
      </div>
      <div v-show="currentPage==2">
        <table>
          <thead>
            <tr>
              <th>序号</th>
              <th colspan="2">检测项目</th>
              <th>技术要求</th>
            </tr>
          </thead>
          <tbody>
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

import { mixObject, copyObject } from '@/shared/util'
import AttrRender from '../components/AttrRender'
import TplRender from '../components/TplRender'

export default {
  name: 'PageOrderRec',
  components: { AttrRender, TplRender },
  props: {
    closeCurrentTab: {
      type: Function
    }
  },
  data() {
    return {
      pages: ['基本信息', '试品主要技术参数', '试验项目及主要技术参数'],
      currentPage: 0,
      donePage: 1,
      currentProject: {},

      orderAttrs: [],
      sampleAttrs: [],
      testItems: [],

      orderInfo: {},
      sampleInfo: {},
      testInfo: {},
    }
  },
  computed: {
    ...mapGetters({
      tabs: 'tabs',
    })
  },
  mounted() {
    if (this.$route.query.id) {
      this.axios.get("projects/" + this.$route.query.id).then(response => {
        this.currentProject = response.data
        this.loadAttrInfo("order-attrs", "orderAttrs", "orderInfo", "order_info")
        this.loadAttrInfo("sample-attrs", "sampleAttrs", "sampleInfo", "sample_info")
        this.loadTestItemInfo()
        this.donePage = 3
      })
    } else {
      this.loadAttrInfo("order-attrs", "orderAttrs", "orderInfo", "order_info")
    }
  },
  methods: {
    /*url:从后台获取属性集合路径，
     *attrCollection:存储属性集合变量
     *infoName:绑定页面属性值变量
     *dbInfoName:数据库中存储属性变量
     */
    loadAttrInfo(url, attrCollection, infoName, dbInfoName) {
      this.axios.get(url).then(response => {
        this[attrCollection] = response.data
        this[attrCollection].map(item => {
          this.$set(this[infoName], item.name, {})
          item["attrs"].map(attr => {
            this.$set(this[infoName][item.name], attr.name, attr.default_value ? attr.default_value : '')
          })
        })
        mixObject(this[infoName], this.currentProject[dbInfoName])
      })
    },
    submitProject() {
      this.axios.post("projects/step-submit/" + this.currentProject["_id"]["$oid"]).then(response => {
        this.closeCurrentTab()
      })
    },
    loadTestItemInfo() {
      this.axios.get("test-items").then(response => {
        this.testItems = response.data
        this.testItems.map(item => {
          this.$set(this.testInfo, item.name, {
            "checked": this.currentProject['test_info'] ? this.currentProject['test_info'].hasOwnProperty(item.name) : false,
            "params": {}
          })
          item["params"].map(param => {
            this.testInfo[item.name]["params"][param.name] = {}
            if (param.attrs) {
              param.attrs.map(attr => {
                this.testInfo[item.name]["params"][param.name][attr.name] = attr.default_value ? attr.default_value : ''
              })
            }
          })
        })
        mixObject(this.testInfo, this.currentProject['test_info'])
      })
    },
    savePage() {
      if (this.currentPage == 0) {
        this.currentProject['order_info'] = copyObject(this.orderInfo)
        this.axios.post("projects/order", JSON.stringify(this.currentProject)).then(response => {
          this.currentProject = response.data['data']
          this.loadAttrInfo("sample-attrs", "sampleAttrs", "sampleInfo", "sample_info")
          this.currentPage = 1
          if (this.donePage < 2) {
            this.donePage = 2
          }
        })
      } else if (this.currentPage == 1) {
        this.currentProject["sample_info"] = copyObject(this.sampleInfo)
        this.axios.post("projects/sample", JSON.stringify(this.currentProject)).then(response => {
          this.currentProject = response.data['data']
          this.loadTestItemInfo()
          this.currentPage = 2
          if (this.donePage < 3) {
            this.donePage = 3
          }
        })
      } else if (this.currentPage == 2) {
        this.currentProject["test_info"] = {}
        for (const key in this.testInfo) {
          if (this.testInfo[key].checked) {
            this.currentProject["test_info"][key] = {
              "params": copyObject(this.testInfo[key]["params"])
            }
          }
        }

        this.axios.post("projects/test-params", JSON.stringify(this.currentProject)).then(response => {
          this.currentProject = response.data['data']
          this.$message({
            message: response.data['message'],
            type: 'success'
          });
        })
      }
    }
  }
}

</script>
<style scoped>
nav {
  margin: 0 15px;
  border: 1px solid #D4D4D4;
  height: 28px;
  line-height: 28px;
  background-color: #F7F7F7;
  margin-top: 8px;
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

.right-operate {
  float: right;
  margin-right: 10px;
}

.done {
  background-image: url("../assets/ordernn.png");
  color: white;
}

.current {
  background-image: url("../assets/ordernh.png");
  color: white;
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

table {
  width: 100%;
  border: 1px solid #ccc;
  border-collapse: collapse;
}

td,
th {
  border: 1px solid #ccc;
}

td {
  padding-left: 5px;
}

</style>
