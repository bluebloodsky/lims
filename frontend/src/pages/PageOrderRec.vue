<template>
  <div>
    <h2>避雷器试验室检测委托协议</h2>
    <div class="nav">
      <ul class="left-info">
        <li class="done"><a @click="cur_page=1">基本信息</a></li>
        <li class="current"><a @click="cur_page=2">试品主要技术参数</a></li>
        <li><a @click="cur_page=3" disable>试验项目及主要技术参数</a></li>
      </ul>
      <div class="right-btn">
        <button>流程复制</button>
        <button>下单</button>
        <button>保存</button>
      </div>
    </div>
    <div class="main-wrapper">
      <div v-show="cur_page==1">
        <div class="box" v-if="clientAttrs&&clientAttrs.length">
          <div class="h">
            <span>委托方</span>
            <div class="right-btn">
              <button type="text"><i class="iconfont icon-search"></i></button>
            </div>
          </div>
          <div class="b">
            <form>
              <div class="form-item" v-for="attr in clientAttrs">
                <span>{{attr.desc}}</span>*
                <div class="right-info">
                  <input v-model="client[attr.name]" disabled>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="box" v-if="serviceAttrs&&serviceAttrs.length">
          <div class="h">
            <span>检测方</span>
          </div>
          <div class="b">
            <form>
              <div class="form-item" v-for="attr in serviceAttrs">
                <span>{{attr.desc}}</span>*
                <div class="right-info">
                  <input v-model="service[attr.name]" disabled>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="box" v-if="protocolAttrs&&protocolAttrs.length">
          <div class="h">
            <span>协议内容</span>
          </div>
          <div class="b">
            <form>
              <div class="form-item" v-for="attr in protocolAttrs">
                <span>{{attr.desc}}</span>
                <div class="right-info">
                  <template v-if="attr.type=='checkbox'">
                    <template v-for="subItem in protocol[attr.name]">
                      <input type="checkbox" class="checkbox" :value="subItem.value" v-model="subItem.checked">
                      <span>{{baseAttrs[attr.options][subItem.value]}}</span>
                    </template>
                  </template>
                  <select v-model="protocol[attr.name]" v-else-if="attr.type=='select'">
                    <option :value="key" v-for="(subItem,key) in baseAttrs[attr.options]">{{subItem}}</option>
                  </select>
                  <input v-model="protocol[attr.name]" v-else>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div v-show="cur_page==2">
        <div class="box" v-if="productBaseAttrs&&productBaseAttrs.length">
          <div class="h">
            <span>检测试品基本信息</span>
          </div>
          <div class="b">
            <form>
              <div class="form-item" v-for="attr in productBaseAttrs">
                <span>{{attr.desc}}</span>
                <div class="right-info">
                  <template v-if="attr.type=='checkbox'">
                    <template v-for="subItem in product[attr.name]">
                      <input type="checkbox" class="checkbox" :value="subItem.value" v-model="subItem.checked">
                      <span>{{baseAttrs[attr.options][subItem.value]}}</span>
                    </template>
                  </template>
                  <select v-model="product[attr.name]" v-else-if="attr.type=='select'">
                    <option :value="key" v-for="(subItem,key) in baseAttrs[attr.options]">{{subItem}}</option>
                  </select>
                  <input v-model="product[attr.name]" v-else-if="attr.type=='input'">
                  <input v-model="product[attr.name]" type="date" v-else-if="attr.type=='date'">
                </div>
              </div>
              <div class="form-item">
                <span>出厂编码</span>
                <div class="right-info">
                  <input v-model="product_code">
                </div>
                <button>+</button>
              </div>
              <div v-for="item in product.product_codes" class="form-code-item">
                <span class="form-code-name">{{item.code}}</span>
                <span class="form-code-desc">试品铭牌及产品外形照片<a>【上传】</a></span>
                <span class="form-code-img">
                <div v-for="img in item.imgs" class="box-img">
                  <img src="../assets/logo.png"> 
                  <button>X</button>
                </div>
                </span>
                <button>-</button>
              </div>
            </form>
          </div>
        </div>
        <div class="box" v-if="productImportAttrs&&productImportAttrs.length">
          <div class="h">
            <span>检测试品主要技术参数</span>
          </div>
          <div class="b">
            <form>
              <div class="form-item" v-for="attr in productImportAttrs">
                <span>{{attr.desc}}</span>
                <div class="right-info">
                  <template v-if="attr.type=='checkbox'">
                    <template v-for="subItem in product[attr.name]">
                      <input type="checkbox" class="checkbox" :value="subItem.value" v-model="subItem.checked">
                      <span>{{baseAttrs[attr.options][subItem.value]}}</span>
                    </template>
                  </template>
                  <select v-model="product[attr.name]" v-else-if="attr.type=='select'">
                    <option :value="key" v-for="(subItem,key) in baseAttrs[attr.options]">{{subItem}}</option>
                  </select>
                  <input v-model="product[attr.name]" v-else-if="attr.type=='input'">
                  <input v-model="product[attr.name]" type="date" v-else-if="attr.type=='date'">
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="box" v-if="productCommonAttrs&&productCommonAttrs.length">
          <div class="h">
            <span>检测试品共性参数信息</span>
          </div>
          <div class="b">
            <form>
              <div class="form-item" v-for="attr in productCommonAttrs">
                <span>{{attr.desc}}</span>
                <div class="right-info">
                  <template v-if="attr.type=='checkbox'">
                    <template v-for="subItem in product[attr.name]">
                      <input type="checkbox" class="checkbox" :value="subItem.value" v-model="subItem.checked">
                      <span>{{baseAttrs[attr.options][subItem.value]}}</span>
                    </template>
                  </template>
                  <select v-model="product[attr.name]" v-else-if="attr.type=='select'">
                    <option :value="key" v-for="(subItem,key) in baseAttrs[attr.options]">{{subItem}}</option>
                  </select>
                  <input v-model="product[attr.name]" v-else-if="attr.type=='input'">
                  <input v-model="product[attr.name]" type="date" v-else-if="attr.type=='date'">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div v-show="cur_page==3">
        <table border v-if="testItemAttrs&&testItemAttrs.length" style="width: 100%;border: 1px solid #ececec; border-collapse: collapse;text-align:left">
          <tbody>
            <tr>
              <th>序号</th>
              <th colspan="2">检测项目</th>
              <th>技术要求</th>
            </tr>
            <template v-for="(item,index) in testItems">
              <template v-if="item.children">
                <tr v-for="(subItem,subIndex) in item.children">
                  <td :rowspan="item.children.length" v-if="subIndex===0">{{index+1}}</td>
                  <td :rowspan="item.children.length" v-if="subIndex===0">
                    <input type="checkbox" :value="item.id" v-model="item.checked">
                    <span>{{item.label}}</span>
                  </td>
                  <td>{{subItem.label}}</td>
                  <td>
                    <TempTestItemParam :item_name="subItem.name" :tpl="subItem.tpl">
                      <template v-if="subItem.params">
                        <template v-for="param in subItem.params">
                          <template v-if="param.type=='radio'">
                            <input type="radio" v-model="param.value" :name="param.name" :slot="param.name+'_'+opt_key" :value="opt_key" v-for="(option,opt_key) in baseAttrs[param.options]">
                          </template>
                          <input :slot="param.name" v-model="param.value" v-else>
                        </template>
                      </template>
                    </TempTestItemParam>
                  </td>
                </tr>
              </template>
              <!--不带子项目-->
              <tr v-else>
                <td>{{index+1}}</td>
                <td colspan="2">
                  <input type="checkbox" :value="item.id" v-model="item.checked">
                  <span>{{item.label}}</span>
                </td>
                <td>
                  <TempTestItemParam :item_name="item.name" :tpl="item.tpl">
                    <template v-if="item.params">
                      <input :slot="param.name" v-for="param in item.params" v-model="param.value">
                    </template>
                  </TempTestItemParam>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
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

var TempTestItemParam = {
  props: ['item_name', 'tpl'],
  created(){
    this.$options.template = this.tpl
  }
}

export default {
  name:'PageOrderRec',
  data() {
    return {
      cur_page: 1,
      product_code: '',
      client: {
        name: '国网电力科学研究院武汉南瑞有限责任公司'
      },
      service: {
        address: '湖北省武汉市洪山区珞瑜路143号'
      },
      testItems: [],
      protocol: {
        client: '1',
        service: '2',
        test_type: [{
          checked: true,
          value: '1'
        }, {
          checked: true,
          value: '2'
        }, {
          checked: true,
          value: '3'
        }, {
          checked: false,
          value: '4'
        }],
        stamp_type: [{
          checked: true,
          value: '1'
        }, {
          checked: true,
          value: '2'
        }, {
          checked: true,
          value: '3'
        }, {
          checked: true,
          value: '4'
        }],
        report_type: '2'
      },
      product: {
        product_codes: [{
          code: '111',
          imgs: ['../assets/test.png', '../assets/logo.png']
        }, {
          code: '111',
          imgs: ['../assets/test.png']
        }, {
          code: '111',
          imgs: ['../assets/logo.png']
        }]
      },
      choose_items: [{
        id: '2',
        params: [{
          name: 'pdbj',
          value: 12.5
        }]
      }, {
        id: '5',
        params: [{
          name: 'bend_select',
          value: 1
        }, {
          name: 'bend_f1',
          value: 12
        }, {
          name: 'stretch_select',
          value: 2
        }]
      }]
    }
  },
  components: { TempTestItemParam },
  computed: {
    ...mapGetters({
      baseAttrs: 'baseAttrs',
      clientAttrs: 'clientAttrs',
      serviceAttrs: 'serviceAttrs',
      protocolAttrs: 'protocolAttrs',
      productBaseAttrs: 'productBaseAttrs',
      productImportAttrs: 'productImportAttrs',
      productCommonAttrs: 'productCommonAttrs',
      testItemAttrs: 'testItemAttrs'
    })
  },
  mounted() {
    this.getTestItems()
  },
  methods: {
    getTestItems() {
      this.testItems = []
      this.testItemAttrs.map(item => {
        var choose_item = this.choose_items.find(sub_item => sub_item.id == item.id)
        var real_item = JSON.parse(JSON.stringify(item))
        real_item.checked = false
        if (real_item.params) {
          real_item.params.map(param => {
            param.value = ""
          })
        }
        if (real_item.children) {
          real_item.children.map(subItem => {
            if (subItem.params) {
              subItem.params.map(param => {
                param.value = ""
              })
            }
          })
        }
        if (choose_item) {
          real_item.checked = true
          if (choose_item.params) {
            choose_item.params.map(real_param => {
              if (real_item.params) {
                real_item.params.map(param => {
                  if (param.name == real_param.name)
                    param.value = real_param.value
                })
              }
              if (real_item.children) {
                real_item.children.map(subItem => {
                  if (subItem.params) {
                    subItem.params.map(param => {
                      if (param.name == real_param.name)
                        param.value = real_param.value
                    })
                  }
                })
              }
            })
          }
        }
        this.testItems.push(real_item)
      })
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

.form-item {
  width: 45%;
  position: relative;
  margin: 5px 10px;
  text-align: left;
  display: inline-block;
}

.form-item>span {
  width: 150px;
  display: inline-block;
  text-align: left;
}

.right-info {
  width: 350px;
  display: inline-block;
}

.right-info>input,
.right-info>select {
  width: 100%;
  font-size: 14px;
}

.right-info>.checkbox {
  width: 15px;
  height: 15px;
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
