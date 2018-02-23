<template>
  <div style="positon:relative">
    <div class="box left-box">
      <div class="h">
        <span>导航</span>
      </div>
      <div class="b">
        <el-menu @select="selectMenu">
          <template v-for="menu in navMenus">
            <el-submenu :index="menu.name" v-if="menu.subItems">
              <template slot="title">
                <span>{{menu.label}}</span>
              </template>
              <el-menu-item :index="subMenu.name" v-for="subMenu in menu.subItems">
                <span>{{subMenu.label}}</span>
              </el-menu-item>
            </el-submenu>
            <el-menu-item :index="menu.name" v-else>
              <span>{{menu.label}}</span>
            </el-menu-item>
          </template>
        </el-menu>
      </div>
    </div>
    <div class="box middle-box">
      <div class="h">
        <span>{{currentAttrs.name_cn}}属性列表</span>
        <div class="right-btn">
          <el-button type="text" @click="logVisible=true"><i class="iconfont icon-log"></i></el-button>
          <el-button type="text" @click="addAttr"><i class="iconfont icon-plus"></i></el-button>
          <el-button type="text" @click="submit"><i class="iconfont icon-submit"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-table :data="currentAttrs.attrs" border>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields" :formatter="cellFormatter">
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template scope="scope">
              <el-button @click.native.prevent="editRow(scope.row)" type="text"><i class="iconfont icon-edit"></i>
              </el-button>
              <el-button @click.native.prevent="delRow(scope.row)" type="text"><i class="iconfont icon-trash"></i>
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
    <div class="right-pad-box" v-if="flg_showRightBox" style="left:70%">
      <div class="h">
        <span>属性编辑</span>
        <div class="right-btn">
          <el-button type="text" @click="hideDetail"><i class="iconfont icon-cancel"></i></el-button>
        </div>
      </div>
      <div class="b">
        <AttrEdit v-model="currentRow" style="padding:15px;" />
      </div>
    </div>
    <el-dialog title="修改记录" :visible.sync="logVisible" width="80%" draggable>
      <el-table :data="currentAttrs.logs" border style="width: 100%">
        <el-table-column property="logTime" label="日期"></el-table-column>
        <el-table-column property="user" label="操作人"></el-table-column>
        <el-table-column label="内容">
          <template scope="scope">
            <div v-for="(content,key) in scope.row.contents" style="border-bottom:#ccc 1px solid" :class="content.type">
              {{showLog(content)}}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template scope="scope">
            <el-button @click.native.prevent="loadVersion(scope.$index)" type="text">加载
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>
<script>
import AttrEdit from '../components/AttrEdit'
import { ATTR_FIELDS } from '@/shared/constants'
import { copyObject, rollbackList } from '@/shared/util'
import {
  mapGetters
} from 'vuex'

export default {
  name: 'PageAttrConfig',
  components: {
    AttrEdit
  },
  data() {
    return {
      logVisible: false,
      fields: [],
      orderClientAttrs: {
        name: "order_client",
        name_cn: "委托方",
        attrs: []
      },
      orderServerAttrs: {
        name: "order_server",
        name_cn: "检测方",
        attrs: []
      },
      orderContentAttrs: {
        name: "order_content",
        name_cn: "委托内容",
        attrs: []
      },
      sampleBaseInfoAttrs: {
        name: "sample_base_info",
        name_cn: "试品基本参数",
        attrs: []
      },
      sampleImportParaAttrs: {
        name: "sample_import_para",
        name_cn: "试品重要参数",
        attrs: []
      },
      sampleMainParaAttrs: {
        name: "sample_main_para",
        name_cn: "试品主要技术参数",
        attrs: []
      },
      currentAttrs: {},
      currentAttrsName: 'orderClientAttrs',
      currentRow: [],
      flg_showRightBox: false,
      navMenus: [{
        name: 'orderAttrs',
        label: '委托单属性',
        subItems: [{
          name: 'orderClientAttrs',
          label: '委托方'
        }, {
          name: 'orderServerAttrs',
          label: '检测方'
        }, {
          name: 'orderContentAttrs',
          label: '委托内容'
        }]
      }, {
        name: 'sampleAttrs',
        label: '试品属性',
        subItems: [{
          name: 'sampleBaseInfoAttrs',
          label: '试品基本参数'
        }, {
          name: 'sampleImportParaAttrs',
          label: '试品重要参数'
        }, {
          name: 'sampleMainParaAttrs',
          label: '试品主要技术参数'
        }]
      }]
    }
  },
  mounted() {
    this.fields = ATTR_FIELDS
    this.axios.get("/order-attrs").then(response => {
      response.data.forEach(item => {
        if (item.name == 'order_client') {
          this.orderClientAttrs = item
        } else if (item.name == 'order_server') {
          this.orderServerAttrs = item
        } else if (item.name == 'order_content') {
          this.orderContentAttrs = item
        }
      })
      return this.axios.get("/sample-attrs")
    }).then(response => {
      response.data.forEach(item => {
        if (item.name == 'sample_base_info') {
          this.sampleBaseInfoAttrs = item
        } else if (item.name == 'sample_import_para') {
          this.sampleImportParaAttrs = item
        } else if (item.name == 'sample_main_para') {
          this.sampleMainParaAttrs = item
        }
      })
    }).catch(err => {
      this.$message({
        message: err['message'],
        type: 'error'
      })
    })
  },
  methods: {
    selectMenu(key, keyPath) {
      this.currentAttrsName = key
      this.currentAttrs = copyObject(this[key])
    },
    cellFormatter(row, column, cellValue) {
      if (Array.isArray(cellValue))
        return cellValue.join('/')
      return cellValue
    },
    addAttr() {
      this.currentRow = {}
      this.currentAttrs.attrs.push(this.currentRow)
      this.flg_showRightBox = true
    },
    editRow(row) {
      this.flg_showRightBox = true
      this.currentRow = row
    },
    delRow(row) {
      this.currentAttrs.attrs.map((attr, index) => {
        if (attr.name == row.name) {
          this.currentAttrs.attrs.splice(index, 1)
        }
      })
    },
    showLog(log) {
      if (log.type == "add") {
        return JSON.stringify(log.newvalue)
      } else if (log.type == "remove") {
        return JSON.stringify(log.oldvalue)
      } else if (log.type == "change") {
        return JSON.stringify(log.oldvalue) + '=>' + JSON.stringify(log.newvalue)
      }
    },
    submit() {
      let url = null
      if (this.currentAttrs.name.indexOf('order') != -1) {
        url = "/order-attrs"
      } else if (this.currentAttrs.name.indexOf('sample') != -1) {
        url = "/sample-attrs"
      }
      if (url) {
        this.axios.post(url, JSON.stringify(this.currentAttrs)).then(response => {
          if (response.data['data']) {
            this.currentAttrs = response.data['data']
          }
          this[this.currentAttrsName] = copyObject(this.currentAttrs)
          this.$message({
            message: response.data['message'],
            type: 'success'
          });
        }).catch(err => {
          console.log("err:" + err)
        })
      }
    },
    hideDetail() {
      this.flg_showRightBox = false
    },
    loadVersion(index) {
      this.currentAttrs.attrs = copyObject(this[this.currentAttrsName].attrs)
      for (let i = 0; i < index; i++) {
        rollbackList(this.currentAttrs.attrs, this[this.currentAttrsName].logs[i].contents)
      }
      this.logVisible = false
    }
  },
  computed: {

  },

}

</script>
<style scoped>
.box {
  border: #ccc 1px solid;
  height: 100%;
  float: left;
  overflow: auto;
  width: 100%;
}

.left-box {
  width: 20%;
}

.middle-box {
  width: 80%;
}

</style>
