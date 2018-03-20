<template>
  <div>
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
          <button type="text" @click="logVisible=true"><i class="iconfont icon-log"></i></button>
          <button type="text" @click="addAttr"><i class="iconfont icon-plus"></i></button>
          <button type="text" @click="submit"><i class="iconfont icon-submit"></i></button>
        </div>
      </div>
      <div class="b">
        <AttrList v-model="currentAttrs.attrs"></AttrList>
      </div>
    </div>
    <el-dialog title="修改记录" :visible.sync="logVisible" width="80%">
      <el-table :data="currentAttrs.logs" border style="width: 100%">
        <el-table-column property="logTime" label="日期"></el-table-column>
        <el-table-column property="user" label="操作人"></el-table-column>
        <el-table-column label="内容">
          <template scope="scope">
            <LogContents :contents="scope.row.contents"></LogContents>
          </template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template scope="scope">
            <button @click="loadVersion(scope.$index)" type="text">加载
            </button>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>
<script>
import AttrList from '../components/AttrList'
import { copyObject, rollbackMap } from '@/shared/util'
import {
  mapGetters
} from 'vuex'

import LogContents from '../components/LogContents'
export default {
  name: 'PageAttrConfig',
  components: {
    AttrList,
    LogContents
  },
  data() {
    return {
      logVisible: false,
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
    this.axios.get("order-attrs").then(response => {
      response.data.forEach(item => {
        if (item.name == 'order_client') {
          this.orderClientAttrs = item
        } else if (item.name == 'order_server') {
          this.orderServerAttrs = item
        } else if (item.name == 'order_content') {
          this.orderContentAttrs = item
        }
      })
      return this.axios.get("sample-attrs")
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
        message: err['error'],
        type: 'error'
      })
    })
  },
  methods: {
    selectMenu(key, keyPath) {
      this.currentAttrsName = key
      this.currentAttrs = copyObject(this[key])
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
    addAttr() {
      this.currentAttrs.attrs.push({})
    },
    loadVersion(index) {
      this.currentAttrs.attrs = copyObject(this[this.currentAttrsName].attrs)
      for (let i = 0; i < index; i++) {
        rollbackMap(this.currentAttrs.attrs, this[this.currentAttrsName].logs[i].contents)
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
  height: 100%;
  float: left;
  overflow: auto;
}

.left-box {
  border-right: 1px solid #ccc;
  width: 20%;
}

.middle-box {
  width: 80%;
}
</style>
