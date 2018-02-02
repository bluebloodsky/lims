<template>
  <div style="height:100vh;position:relative;">
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
          <el-button type="text" @click="addAttr"><i class="iconfont icon-plus"></i></el-button>
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
          <el-button type="text" @click="submit"><i class="mdi iconfont icon-submit"></i></el-button>
          <el-button type="text" @click="hideDetail"><i class="iconfont icon-cancel"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-form :form="currentAttr" label-width="120px" style="text-align:left;padding: 20px">
          <el-form-item label="属性名">
            <el-input v-model="currentAttr.name"></el-input>
          </el-form-item>
          <el-form-item label="属性描述">
            <el-input v-model="currentAttr.name_cn"></el-input>
          </el-form-item>
          <el-form-item label="类型">
            <el-select v-model="currentAttr.type">
              <el-option :label="attrType.type_cn" :value="attrType.type" v-for="attrType in attrTypes" />
            </el-select>
          </el-form-item>
          <el-form-item label="选项">
            <TodoList />
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import TodoList from '../components/TodoList'

import {
  mapGetters
} from 'vuex'

export default {
  name: 'PageAttrConfig',
  data() {
    return {
      orderClientAttrs: [],
      orderServerAttrs: [],
      orderContentAttrs: [],
      sampleBaseInfoAttrs: [],
      sampleImportParaAttrs: [],
      sampleMainParaAttrs: [],
      currentAttrs: [],
      currentAttr: [],
      flg_showRightBox: false,
      fields: [{
        name: 'name',
        caption: '属性名'
      }, {
        name: 'name_cn',
        caption: '属性描述'
      }, {
        name: 'type',
        caption: '类型'
      }, {
        name: 'options',
        caption: '选项'
      }],
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
          label: '协议内容'
        }]
      }, {
        name: 'sampleAttrs',
        label: '试品属性',
        subItems: [{
          name: 'sampleBaseInfoAttrs',
          label: '试品基本属性'
        }, {
          name: 'sampleImportParaAttrs',
          label: '试品共性参数属性'
        }, {
          name: 'sampleMainParaAttrs',
          label: '试品主要技术参数属性'
        }]
      }]
    }
  },
  mounted() {
    this.axios.get("/order-attrs?lab=blq").then(response => {
      response.data.forEach(item => {
        if (item.name == 'order_client_attrs') {
          this.orderClientAttrs = item
        } else if (item.name == 'order_server_attrs') {
          this.orderServerAttrs = item
        } else if (item.name == 'order_content_attrs') {
          this.orderContentAttrs = item
        }
      })
      return this.axios.get("/sample-attrs?lab=blq")
    }).then(response => {
      response.data.forEach(item => {
        if (item.name == 'sample_base_info_attrs') {
          this.sampleBaseInfoAttrs = item
        } else if (item.name == 'sample_import_para_attrs') {
          this.sampleImportParaAttrs = item
        } else if (item.name == 'sample_main_para_attrs') {
          this.sampleMainParaAttrs = item
        }
      })
    })
  },
  computed: {
    ...mapGetters({
      attrTypes: 'attrTypes'
    }),
  },
  components:{
    TodoList
  },
  methods: {
    selectMenu(key, keyPath) {
      this.currentAttrs = this[key]
    },
    cellFormatter(row, column, cellValue) {
      if (Array.isArray(cellValue))
        return cellValue.join('/')
      return cellValue
    },
    addAttr() {

    },
    editRow(row) {
      this.flg_showRightBox = true
      this.currentAttr = row
    },
    delRow(row) {

    },
    submit() {

    },
    hideDetail() {
      this.flg_showRightBox = false
    }
  }
}

</script>
<style scoped>
.box {
  border: #ccc 1px solid;
  height: 100vh;
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
