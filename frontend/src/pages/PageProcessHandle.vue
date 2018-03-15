<template>
  <div>
    <div class="box top-box">
      <div class="h">
        <span v-if="currentProject&&currentProject.order_info&&currentProject.order_info">
      "{{currentProject.order_info.order_content.order_code}}"</span>的业务办理流程
        <div class="right-btn">
          <el-button type="text"  @click="stepRollback" v-if="currentProject.step&&currentProject.step.name!='ORDER_RECEIVE'"><<回退
            </el-button>
              <el-button type="text"  @click="stepSubmit"  v-if="currentProject.step&&currentProject.step.name!='ARCHIVE'">>>移交</el-button>
        </div>
      </div>
      <div class="b">
        <ul class="tabs">
          <li  v-for="(tabName,tabIndex) in tabPages">
            <a :class="{active:activeTab==tabIndex}" @click="activeTab=tabIndex">{{tabName}}</a>
          </li>
        </ul>
        <div>
          <el-table :data="steps" border v-show="activeTab == 0">
            <el-table-column type="index" align="center">
            </el-table-column>
            <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>
    <div class="box bottom-box">
      <div class="h" v-if="currentProject.step">
        <el-button type="text" v-if="currentProject.step.name == 'ORDER_RECEIVE'" @click="openOrderRec">协议修改</el-button>
        <el-button type="text" v-if="currentProject.step.name != 'ORDER_RECEIVE'">协议详情查看</el-button>
        <el-button type="text" v-if="currentProject.step.name == 'RECORD_EDIT'">原始记录编辑</el-button>
        <el-button type="text" v-if="currentProject.step.name == 'RECORD_REVIEW'">原始记录查看</el-button>
        <el-button type="text" v-if="currentProject.step.name == 'RECORD_REVIEW'">试验报告查看</el-button>
    </div>
      <div class="b">
      </div>
    </div>
  </div>
</template>
<script>
import { copyObject } from '@/shared/util'

export default {
  name: 'PageProcessHandle',
  props: {
    closeCurrentTab: {
      type: Function
    }
  },
  data() {
    return {
      tabPages: ["办理过程", "流程图"],
      pre_url: '/home/',
      activeTab: 0,
      proId:'',
      currentProject: {},
      fields: [{
        name: 'alias',
        caption: '节点名称'
      }, {
        name: 'status',
        caption: '状态'
      }, {
        name: 'user',
        caption: '承办人'
      }, {
        name: 'suggestion',
        caption: '意见'
      }, {
        name: 'handleAt',
        caption: '办理时间'
      }]
    }
  },
  computed: {
    steps() {
      let steps = this.currentProject && this.currentProject.doneSteps ? copyObject(this.currentProject.doneSteps) : []
      if (this.currentProject && this.currentProject.step) {
        steps.push({
          alias: this.currentProject.step.alias
        })
      }
      return steps
    }
  },
  mounted() {
    if (!this.$route.query.id) {
      this.closeCurrentTab()
    }
    this.proId = this.$route.query.id
    this.axios.get("projects/" + this.proId).then(response => {
      this.currentProject = response.data
    })
  },
  methods: {
    openOrderRec() {
      let url = "order-rec"
      this.$store.commit('addTab', url)
      this.$router.push({ path: this.pre_url + url + "?id=" + this.proId })
    },
    stepSubmit() {
      this.axios.post("projects/step-submit/" + this.proId).then(response => {
        this.closeCurrentTab()
      })
    },
    stepRollback() {
      this.axios.post("projects/step-rollback/" + this.proId).then(response => {
        this.closeCurrentTab()
      })
    }
  }
}

</script>
<style scoped>

.top-box {
  height: 60%;
}

.bottom-box {
  height: 40%;
}

</style>
