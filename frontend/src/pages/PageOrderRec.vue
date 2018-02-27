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

import { mixObject, copyObject } from '@/shared/util'
import AttrRender from '../components/AttrRender'
import TplRender from '../components/TplRender'

export default {
  name: 'PageOrderRec',
  components: { AttrRender, TplRender },
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
  computed: {},
  mounted() {
    if (this.$route.query.id) {
      this.axios.get("/projects/" + this.$route.query.id).then(response => {
        this.currentProject = response.data
        this.loadAttrInfo("order-attrs", "orderAttrs", "orderInfo", "order_info")
        this.loadAttrInfo("sample-attrs", "sampleAttrs", "sampleInfo", "sample_info")
        this.loadTestItemInfo()
        this.donePage = 3
      })
    }else{
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
    loadTestItemInfo() {
      this.axios.get("test-items").then(response => {
        this.testItems = response.data
        this.testItems.map(item => {
          this.$set(this.testInfo, item.name, {
            "checked": this.currentProject['test_info']?this.currentProject['test_info'].hasOwnProperty(item.name):false,
            "params": {}
          })
          item["params"].map(param => {
            this.testInfo[item.name]["params"][param.name] = {}
            param.attrs.map(attr => {
              this.testInfo[item.name]["params"][param.name][attr.name] = attr.default_value ? attr.default_value : ''
            })
          })
        })
        mixObject(this.testInfo, this.currentProject['test_info'])
      })
    },
    savePage() {
      if (this.currentPage == 0) {
        this.currentProject['order_info'] = copyObject(this.orderInfo)
        this.axios.post("/projects/order", JSON.stringify(this.currentProject)).then(response => {
          this.currentProject = response.data['data']
          this.loadAttrInfo("sample-attrs", "sampleAttrs", "sampleInfo", "sample_info")
          this.currentPage = 1
          if (this.donePage < 2) {
            this.donePage = 2
          }
        })
      } else if (this.currentPage == 1) {
        this.currentProject["sample_info"] = copyObject(this.sampleInfo)
        this.axios.post("/projects/sample", JSON.stringify(this.currentProject)).then(response => {
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

        this.axios.post("/projects/test-params", JSON.stringify(this.currentProject)).then(response => {
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
