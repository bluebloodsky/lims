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
          <el-button type="text" @click="logVisible=true"><i class="iconfont icon-log"></i></el-button>
        </div>
      </div>
      <div class="b">
        <TplRender :tpl="currentTpl.data.tpl" :attrs="currentTpl.data.attrs" v-if="mode==1">
        </TplRender>
        <textarea v-model="currentTpl.data.tpl" v-else></textarea>
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
        <el-table :data="currentTpl.data.attrs" border>
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
    <el-dialog title="修改记录" :visible.sync="logVisible" width="80%" draggable>
      <el-table :data="currentTpl.data.logs" border style="width: 100%">
        <el-table-column property="logTime" label="日期"></el-table-column>
        <el-table-column property="user" label="操作人"></el-table-column>
        <el-table-column label="内容">
          <template scope="scope">
            <div v-for="(content,key) in scope.row.contents" style="border-bottom:#fff 1px solid">
              <span v-if="content.type" :class="content.type">
              【{{key}}】: {{content.oldvalue}}
              <span v-if="content.type=='change'" style="color:red">
                ==>                 
              </span> {{content.newvalue}}
              </span>
              <template v-else>
                【{{key}}】:
                <div v-for="(subContent,subKey) in content" :class="subContent.type">
                  【{{subKey}}】: 
                  <span v-if="content.type" :class="content.type">
                  {{subContent.oldvalue}}
                  <span v-if="subContent.type=='change'" style="color:red">
                   ==>                  
                  </span> {{subContent.newvalue}}
                </span>
                <template v-else>
                  <div v-for="(grandchildContent,grandchildKey) in subContent" :class="grandchildContent.type">
                  【{{grandchildKey}}】: 
                  <span v-if="grandchildContent.type" :class="grandchildContent.type">
                  {{grandchildContent.oldvalue}}
                  <span v-if="grandchildContent.type=='change'" style="color:red">
                   ==>                  
                  </span> {{grandchildContent.newvalue}}
                </span>
              </div>
                </template>
                </div>
              </template>
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
import {
  mapGetters,
  mapActions
} from 'vuex'
import { ATTR_FIELDS ,ATTR_TYPES} from '@/shared/constants'
import AttrEdit from '../components/AttrEdit'
import TplRender from '../components/TplRender'
import { copyObject, rollbackArray } from '@/shared/util'
var TempTestItemParam = {
  props: ['tpl'],
  created() {
    this.$options.template = '<div>' + this.tpl + '</div>'
  }
}

export default {
  name: 'PageTplConfig',
  components: { TempTestItemParam, AttrEdit, TplRender },
  data() {
    return {
      mode: 0,
      flg_showRightBox: false,
      currentRow: {},
      testItems: [],
      currentTpl: {
        order: [],
        data: {}
      },
      attrFields: [],
      attrTypes:[],
      logVisible: false
    }
  },
  mounted() {
    this.attrFields = ATTR_FIELDS
    this.attrTypes = ATTR_TYPES
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
      else if(column.property=='attr_type'){
        let attr_type = this.attrTypes.find(i=>i.type == cellValue)
        return attr_type?attr_type.type_cn:cellValue
      }
      return cellValue
    },
    addAttr() {
      this.currentRow = {}
      this.currentTpl.data.attrs.push(this.currentRow)
      this.flg_showRightBox = true
    },
    editRow(row) {
      this.flg_showRightBox = true
      this.currentRow = row
    },
    delRow(row) {
      this.currentTpl.data.attrs.map((attr, index) => {
        if (attr.name == row.name) {
          this.currentTpl.data.attrs.splice(index, 1)
        }
      })
    },
    submit() {
      let infos = this.currentTpl.order
      let testItem = copyObject(this.testItems[infos[0]])
      testItem[infos[1]][infos[2]] = this.currentTpl.data
      this.axios.post("/test-items", JSON.stringify(testItem)).then(response => {
        if (response.data['data']) {
          this.testItems[infos[0]] = response.data['data']
          this.currentTpl.data = response.data['data'][infos[1]][infos[2]]
        }
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

      this.currentTpl.data = copyObject(this.testItems[l_infos[0]][l_infos[1]][l_infos[2]])
      this.currentTpl.order = l_infos
      let oldMod = this.mode
      this.mode = 0
      if (this.mode != oldMod) {
        setTimeout(() => {
          this.mode = oldMod
        }, 0)
      }
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
    loadVersion(index) {
      this.logVisible = false
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
