<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <span>项目</span>
      </div>
      <div class="b">
        <el-menu @select="selectMenu">
          <el-submenu :index="menu_index+''" v-for="(menu,menu_index) in testItems">
            <template slot="title">
              <span>{{menu.name_cn}}</span>
            </template>
            <el-menu-item-group>
              <template slot="title">项目参数模板</template>
              <el-menu-item :index="menu_index+'-params-'+ param_index" v-for="(param,param_index) in menu.params">
                <span>{{param.name_cn}}</span>
              </el-menu-item>
            </el-menu-item-group>
            <el-menu-item-group>
              <template slot="title">原始记录模板</template>
              <el-menu-item :index="menu_index+'-records-'+ record_index" v-for="(record,
              record_index) in menu.records">
                <span></span>
              </el-menu-item>
            </el-menu-item-group>
          </el-submenu>
        </el-menu>
      </div>
    </div>
    <div class="box top-box">
      <div class="h">
        <span>项目模板</span>
        <div class="right-btn">
          <el-button type="text" @click="submit"><i class="iconfont icon-submit"></i></el-button>
          <el-button type="text" @click="mode=1-mode" :class="{'mode-edit': mode==0}"><i class="iconfont icon-edit"></i></el-button>
        </div>
      </div>
      <div class="b">
        <AttrRender :tpl="currentTpl.tpl" :attrs="currentTpl.attrs" v-if="mode==1">
        </AttrRender>
        <textarea v-model="currentTpl.tpl" v-else></textarea>
      </div>
    </div>
    <div class="box bottom-box">
      <div class="h">
        <span>项目参数</span>
        <div class="right-btn">
          <el-button type="text" @click="addAttr"><i class="iconfont icon-plus"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-table :data="currentTpl.attrs" border>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in attrFields" :formatter="cellFormatter">
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
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
import { ATTR_FIELDS } from '@/shared/constants'
import AttrEdit from '../components/AttrEdit'
import AttrRender from '../components/AttrRender'
import { copyObject, rollbackArray } from '@/shared/util'
var TempTestItemParam = {
  props: ['tpl'],
  created() {
    this.$options.template = '<div>' + this.tpl + '</div>'
  }
}

export default {
  name: 'PageTplConfig',
  components: { TempTestItemParam, AttrEdit, AttrRender },
  data() {
    return {
      mode: 0,
      flg_showRightBox: false,
      infos: [],
      currentRow: {},
      testItems: [],
      currentItem: {
        tpl: '',
        attrs: []
      },
      currentTpl: {},
      attrFields: []
    }
  },
  mounted() {
    this.attrFields = ATTR_FIELDS
    this.axios.get("/test-items").then(response => {
      this.testItems = response.data
    }).catch(e => {

    })
  },
  methods: {
    hideDetail() {
      this.flg_showRightBox = false
    },
    cellFormatter(row, column, cellValue) {
      if (Array.isArray(cellValue))
        return cellValue.join('/')
      else if (typeof cellValue == 'boolean') {
        return cellValue ? '是' : '否'
      }
      return cellValue
    },
    addAttr() {
      this.currentRow = {}
      this.currentTpl.attrs.push(this.currentRow)
      this.flg_showRightBox = true
    },
    editRow(row) {
      this.flg_showRightBox = true
      this.currentRow = row
    },
    delRow(row) {
      this.currentTpl.attrs.map((attr, index) => {
        if (attr.name == row.name) {
          this.currentTpl.attrs.splice(index, 1)
        }
      })
    },
    submit() {
      this.axios.post("/test-items", JSON.stringify(this.currentItem)).then(response => {
        if (response.data['data']) {
          this.currentItem = response.data['data']
        }
        this.testItems[this.infos[0]] = copyObject(this.currentItem)
        this.$message({
          message: response.data['message'],
          type: 'success'
        });
      }).catch(err => {
        console.log("err:" + err)
      })
    },
    selectMenu(key) {
      let l_infos = key.split('-')
      if (this.infos && this.infos[0] != l_infos[0]) {
        this.currentItem = copyObject(this.testItems[l_infos[0]])
      }
      this.currentTpl = this.currentItem[l_infos[1]][l_infos[2]]
      this.infos = l_infos
      let oldMod = this.mode
      this.mode = 0
      if (this.mode != oldMod) {
        setTimeout(() => {
          this.mode = oldMod
        }, 0)
      }
    }
  }
}

</script>
<style scoped>
.box {
  border: #ccc 1px solid;
  height: 100%;
  float: left;
  overflow: auto;
}

.left-box {
  width: 20%;
}

.top-box {
  width: 80%;
  height: 50%;
}

.bottom-box {
  width: 80%;
  height: 50%;
}

button.mode-edit {
  background-color: #fff;
  border: 1px solid #aaa;
}

textarea {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  font-size: 16px;
}

table.dataintable {
  margin-top: 15px;
  border-collapse: collapse;
  border: 1px solid #aaa;
  width: 100%;
  border-spacing: 0px;
}

td input {
  margin: -1px;
  padding: 0;
  height: 25px;
  width: 99%;
  font-size: 18px;
}

.disabled {
  background-color: rgb(235, 235, 228);
}

</style>
