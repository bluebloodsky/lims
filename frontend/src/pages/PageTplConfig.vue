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
            <el-menu-item-group v-if="menu.params">
              <template slot="title">项目参数模板</template>
              <el-menu-item :index="menu_index+'-params-'+ param_index" v-for="(param,param_index) in menu.params">
                <span>{{param.name_cn}}</span>
              </el-menu-item>
            </el-menu-item-group>
            <el-menu-item-group v-if="menu.records">
              <template slot="title">原始记录模板</template>
              <el-menu-item :index="menu_index+'-records-'+ record_index" v-for="(record,record_index) in menu.records">
                <span>{{record.name_cn}}</span>
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
          <button type="text" @click="submit"><i class="iconfont icon-submit"></i></button>
          <button type="text" @click="mode=1-mode" :class="{'mode-edit': mode==0}"><i class="iconfont icon-edit"></i></button>
          <button type="text" @click="logVisible=true"><i class="iconfont icon-log"></i></button>
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
          <button type="text" @click="addAttr"><i class="iconfont icon-plus"></i></button>
        </div>
      </div>
      <div class="b">
        <AttrList v-model="currentTpl.data.attrs"></AttrList>
      </div>
    </div>
    <el-dialog title="修改记录" :visible.sync="logVisible" width="80%" draggable>
      <el-table :data="logs" border style="width: 100%">
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
import {
  mapGetters,
  mapActions
} from 'vuex'
import AttrList from '../components/AttrList'
import TplRender from '../components/TplRender'
import LogContents from '../components/LogContents'
import { copyObject, rollbackMap } from '@/shared/util'

export default {
  name: 'PageTplConfig',
  components: {  AttrList, TplRender, LogContents },
  data() {
    return {
      mode: 0,
      testItems: [],
      currentTpl: {
        order: [],
        data: {}
      },
      logVisible: false
    }
  },
  computed: {
    logs() {
      let result = []
      let order = this.currentTpl.order
      if (order && order.length) {
        let all_logs = this.testItems[order[0]].logs
        if (all_logs && all_logs.length) {
          all_logs.map(log => {
            if (log.contents &&
              log.contents[order[1]] &&
              log.contents[order[1]][order[2]]) {
              result.push({
                logTime: log.logTime,
                user: log.user,
                contents: log.contents[order[1]][order[2]]
              })
            }
          })
          result.push({})
        }
      }
      return result
    }
  },
  created() {
    this.axios.get("test-items").then(response => {
      this.testItems = response.data
    }).catch(e => {
      this.$message({
        message: err['error'],
        type: 'error'
      })
    })
  },
  methods: {
    addAttr() {
      this.currentTpl.data.attrs.push({})
    },
    submit() {
      let infos = this.currentTpl.order
      let testItem = copyObject(this.testItems[infos[0]])
      testItem[infos[1]][infos[2]] = this.currentTpl.data
      this.axios.post("test-items", JSON.stringify(testItem)).then(response => {
        if (response.data['data']) {
          this.$set(this.testItems, infos[0], response.data['data'])
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
      if (!this.currentTpl.data["attrs"]) {
        this.$set(this.currentTpl.data, "attrs", [])
      }
      if (!this.currentTpl.data["tpl"]) {
        this.$set(this.currentTpl.data, "tpl", '')
      }
      this.currentTpl.order = l_infos
      if (this.mode == 1) {
        this.mode = 0
        this.$nextTick(() => {
          this.mode = 1
        })
      }
    },
    loadVersion(index) {
      this.logVisible = false
      let l_infos = this.currentTpl.order
      let data = copyObject(this.testItems[l_infos[0]][l_infos[1]][l_infos[2]])
      for (let i = 0; i < index; i++) {
        rollbackMap(data, this.logs[i].contents)
      }
      this.currentTpl.data = data
      this.logVisible = false
    }
  }
}

</script>
<style scoped>
.box {
  border-top: #ccc 1px solid;
  height: 100%;
  float: left;
  overflow: auto;
}

.left-box {
  border-right: #ccc 1px solid;
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
  background-color: #066;
  border: 1px solid #ccc;
  color: #fff;
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
</style>
