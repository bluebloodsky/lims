<template>
  <div>
    <div class="box main-box">
      <div class="h">
        <span>流程图</span>
        <div class="right-btn">
          <button type="text" @click="submit"><i class="iconfont icon-submit"></i></button>
        </div>
      </div>
      <div class="b">
        <svg class="flowChart" @mouseup="drop" @mousemove="move" @click="contextmenuPositon=[-9999,-9999]">
          <StepSvg :key="item.id" v-for="(item,index) in mySteps" :item="item" @grab="grab" @rightClick="contextmenu" @dblclick="editStep" :selected="stepSelIndex==index">
          </StepSvg>
          <ArrowSvg :item="item" @grab="grabArrow" v-for="(item,index) in arrows" :selected="arrowSelIndex==index"></ArrowSvg>
        </svg>
        <ul :style="{left:contextmenuPositon[0] + 'px',top:contextmenuPositon[1] + 'px'}" class="contextmenu" @contextmenu.prevent>
          <li><a @click="editStep">编辑</a></li>
        </ul>
      </div>
    </div>
    <div class="box right-box">
      <div class="h">
        <span>工作流组件</span>
      </div>
      <div class="b">
        <button-group>
          <button :class="{choose:eventStatus==1}" @click="eventStatus = 1">对象</button>
          <button :class="{choose:eventStatus==2}" @click="eventStatus = 2">正向连接</button>
          <button :class="{choose:eventStatus==3}" @click="eventStatus = 3">回退连接</button>
        </button-group>
        <svg class="wfcs" @mouseup="drop">
          <StepSvg :item="item" @grab="grab" v-for="item in wfcs"></StepSvg>
        </svg>
      </div>
    </div>
    <el-dialog title="属性查看" :visible.sync="dialogVisible">
      <el-form label-width="120px" onsubmit="return false;">
        <el-form-item label="名称">
          <el-input v-model="selectStep.name" disabled></el-input>
        </el-form-item>
        <el-form-item label="描述">
          <el-input v-model="selectStep.name_cn" disabled></el-input>
        </el-form-item>
        <el-form-item label="别名">
          <el-input v-model="selectStep.alias"></el-input>
        </el-form-item>
        <el-form-item label="下级环节">
          <el-input :value="getStepAliasByIds(selectStep.nextSteps)" disabled></el-input>
        </el-form-item>
        <el-form-item label="回退环节">
          <el-input :value="getStepAliasByIds(selectStep.rollbackSteps)" disabled></el-input>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import { WORKFLOW_STEPS } from '@/shared/constants'
import { copyObject, remove } from '@/shared/util'
import StepSvg from '@/components/StepSvg'
import ArrowSvg from '@/components/ArrowSvg'
export default {
  name: 'PageWorkflowConfig',
  components: { StepSvg, ArrowSvg },
  data() {
    return {
      contextmenuPositon: [-9999, -9999], //svg组件右键菜单位置
      cursePoint: [], //记录svg鼠标点击位置
      size: [200, 40], //定义组件大小
      eventStatus: 1, //事件状态, 1对象 ,2正向连接,3:回退连接
      moveFlg: false, //处于移动状态标识
      stepSelIndex: -1, //选中组件      
      arrowSelIndex: -1, //选中箭头
      newStep: null,
      wfcs: [], //程序定义的流程集合
      mySteps: [],
      dialogVisible: false
    }
  },
  mounted() {
    this.wfcs = WORKFLOW_STEPS
    this.axios.get('workflows').then(response => {
      this.mySteps = response.data
    }).catch(err => {
      this.$message({
        message: err['error'],
        type: 'error'
      })
    })

    window.onkeydown = e => {
      if (e.code == 'Delete') {
        if (this.stepSelIndex != -1) {
          let delStepId = this.mySteps[this.stepSelIndex].id
          this.mySteps.splice(this.stepSelIndex, 1)
          this.mySteps.map(step => {
            remove(step.nextSteps, delStepId)
            remove(step.rollbackSteps, delStepId)
          })
          this.stepSelIndex = -1
        } else if (this.arrowSelIndex != -1) {
          let selArrow = this.arrows[this.arrowSelIndex]
          let step = this.mySteps.find(item => selArrow.id == item.id)
          if (selArrow.rollBackFlg) {
            remove(step.rollbackSteps, selArrow.nextId)
          } else {
            remove(step.nextSteps, selArrow.nextId)
          }
          this.arrowSelIndex = -1
        }
      }
    }
  },
  computed: {
    arrows() {
      let arrows = []
      this.mySteps.map(step => {
        step.nextSteps.map(nextStepId => {
          let nexStep = this.mySteps.find(item => item.id == nextStepId)
          if (nexStep) {
            arrows.push({
              id: step.id,
              nextId: nextStepId,
              points: this.getArrowPoints(step.point, nexStep.point, false),
              rollBackFlg: false
            })
          }
        })

        step.rollbackSteps.map(nextStepId => {
          let nexStep = this.mySteps.find(item => item.id == nextStepId)
          if (nexStep) {
            arrows.push({
              id: step.id,
              nextId: nextStepId,
              points: this.getArrowPoints(step.point, nexStep.point, true),
              rollBackFlg: true
            })
          }
        })

      })

      return arrows
    },
    selectStep: {
      get() {
        return this.mySteps[this.stepSelIndex] ? this.mySteps[this.stepSelIndex] : {}
      },
      set(val) {
        this.mySteps[this.stepSelIndex] = val
      }
    },
  },
  methods: {
    submit() {
      let loseKeySteps = []
      this.wfcs.map(wtf => {
        if (wtf.key_link && !this.mySteps.find(step => step.name == wtf.name)) {
          loseKeySteps.push(wtf.name_cn)
        }
      })
      if (loseKeySteps.length > 0) {
        this.$message({
          message: '缺失关键环节[' + loseKeySteps + ']',
          type: 'info'
        })
        return
      }
      this.axios.post('workflows', JSON.stringify(this.mySteps)).then(response => {
        this.$message({
          message: '更新成功',
          type: 'success'
        })
      }).catch(err => {
        this.$message({
          message: err['error'],
          type: 'error'
        })
      })
    },
    editStep() {
      this.contextmenuPositon = [-9999, -9999]
      if (this.eventStatus == 1)
        this.dialogVisible = true
    },
    getStepAliasByIds(ids) {
      return ids ? this.mySteps.filter(step => ids.includes(step.id)).map(step => step.alias).join() : ''
    },
    getArrowPoints(startPoint, endPoint, rollBackFlg) {
      if (startPoint[1] + this.size[1] < endPoint[1]) {
        if (rollBackFlg) {
          return [startPoint[0] + this.size[0] / 2 - 10, startPoint[1] + this.size[1],
            endPoint[0] + this.size[0] / 2 - 10, endPoint[1]
          ]
        } else {
          return [startPoint[0] + this.size[0] / 2 + 10, startPoint[1] + this.size[1],
            endPoint[0] + this.size[0] / 2 + 10, endPoint[1]
          ]
        }
      } else if (startPoint[1] - this.size[1] > endPoint[1]) {
        if (rollBackFlg) {
          return [startPoint[0] + this.size[0] / 2 - 10, startPoint[1], endPoint[0] + this.size[0] / 2 - 10, endPoint[1] + this.size[1]]
        } else {
          return [startPoint[0] + this.size[0] / 2 + 10, startPoint[1], endPoint[0] + this.size[0] / 2 + 10, endPoint[1] + this.size[1]]
        }
      } else if (startPoint[0] + this.size[0] < endPoint[0]) {
        if (rollBackFlg) {
          return [startPoint[0] + this.size[0], startPoint[1] + this.size[1] / 2 - 10, endPoint[0], endPoint[1] + this.size[1] / 2 - 10]
        } else {
          return [startPoint[0] + this.size[0], startPoint[1] + this.size[1] / 2 + 10, endPoint[0], endPoint[1] + this.size[1] / 2 + 10]
        }
      } else if (startPoint[0] - this.size[0] > endPoint[0]) {
        if (rollBackFlg) {
          return [startPoint[0], startPoint[1] + this.size[1] / 2 - 10, endPoint[0] + this.size[0], endPoint[1] + +this.size[1] / 2 - 10]
        } else {
          return [startPoint[0], startPoint[1] + this.size[1] / 2 + 10, endPoint[0] + this.size[0], endPoint[1] + +this.size[1] / 2 + 10]
        }
      } else {
        if (rollBackFlg) {
          return [startPoint[0], startPoint[1] + this.size[1] / 2 - 10, endPoint[0] + this.size[0], endPoint[1] + +this.size[1] / 2 - 10]
        } else {
          return [startPoint[0], startPoint[1] + this.size[1] / 2 + 10, endPoint[0] + this.size[0], endPoint[1] + +this.size[1] / 2 + 10]
        }
      }
    },
    contextmenu(e, item) {
      this.contextmenuPositon = [e.layerX, e.layerY]
    },
    grabArrow(e, item) {
      this.stepSelIndex = -1
      this.arrows.forEach((arrow, index) => {
        if (arrow.id == item.id && arrow.nextId == item.nextId) {
          this.arrowSelIndex = index
          return
        }
      })
    },

    grab(e, item) {
      if (this.arrowSelIndex != -1) { //当前为箭头
        this.arrowSelIndex = -1
      }
      if (item.id) { //该环节已存在
        let clickStepIndex, clickStep
        this.mySteps.forEach((stepItem, index) => {
          if (stepItem.id == item.id) {
            clickStepIndex = index
            clickStep = stepItem
          }
        })
        if (this.eventStatus == 2 || this.eventStatus == 3) { //连接状态
          if (this.stepSelIndex != -1) {
            if (this.stepSelIndex != clickStepIndex) { //进行连接
              let startStep = this.mySteps[this.stepSelIndex]
              if (this.eventStatus == 2 &&
                startStep.nextSteps.indexOf(clickStep.id) == -1 &&
                clickStep.nextSteps.indexOf(startStep.id) == -1
              ) {
                startStep.nextSteps.push(clickStep.id)
              } else if (this.eventStatus == 3 &&
                startStep.rollbackSteps.indexOf(clickStep.id) == -1 &&
                clickStep.rollbackSteps.indexOf(startStep.id) == -1
              ) {
                startStep.rollbackSteps.push(clickStep.id)
              }
            } else {
              this.stepSelIndex = -1;
              return
            }
          }
        }
        this.stepSelIndex = clickStepIndex
      } else if (item.key_link &&
        this.mySteps.find(step => step.name == item.name)) { //关键环节已存在
        this.$message({
          message: item.name_cn + "为关键环节，只能添加一次",
          type: 'info'
        })
        return
      } else { //新建 
        this.stepSelIndex = -1;
        this.newStep = item
      }
      if (this.eventStatus == 1) {
        this.cursePoint = [e.layerX, e.layerY]
        this.moveFlg = true
      }
    },
    drop(e) {
      if (this.stepSelIndex == -1) {
        this.newStep = null
        return
      }
      if (this.moveFlg) {
        this.moveFlg = false
      }
    },
    move(e) {
      if (this.stepSelIndex == -1 && !this.newStep) {
        return
      }
      if (this.moveFlg) {
        let newCursePoint = [e.layerX, e.layerY]
        if (this.stepSelIndex == -1) {
          let hasSameStepNum = this.mySteps.filter(step => step.name == this.newStep.name).length
          if (hasSameStepNum) {
            this.newStep.id = this.newStep.name + '_' + hasSameStepNum
            this.newStep.alias = this.newStep.name_cn + '_' + hasSameStepNum
          } else {
            this.newStep.id = this.newStep.name
            this.newStep.alias = this.newStep.name_cn
          }
          this.newStep.point = [newCursePoint[0] - 50, newCursePoint[1] - 20]
          this.newStep.nextSteps = []
          this.newStep.rollbackSteps = []

          this.mySteps.push(copyObject(this.newStep))
          this.newStep = null
          this.stepSelIndex = this.mySteps.length - 1
        } else {
          let currentStep = this.mySteps[this.stepSelIndex]
          currentStep.point = [currentStep.point[0] + newCursePoint[0] - this.cursePoint[0], currentStep.point[1] + newCursePoint[1] - this.cursePoint[1]]
        }
        this.cursePoint = newCursePoint
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
  width: 100%;
}

.main-box {
  width: 80%;
}

.right-box {
  width: 20%;
}

.flowChart {
  position: relative;
  width: 99%;
  height: 99%;
}

.wfcs {
  position: relative;
  width: 99%;
  height: 80%;
}

.contextmenu {
  border: 1px solid #bbb;
  border-bottom: none;
  position: absolute;
  background-color: #fff;
}

.contextmenu>li {
  width: 100px;
  border-bottom: 1px solid #bbb;
}

.contextmenu>li>a {
  height: 33px;
  line-height: 33px;
  text-align: center;
  display: block;
}

.contextmenu>li:hover {
  background-color: #eee;
}

.choose {
  background-color: #f4e284;
}

</style>
