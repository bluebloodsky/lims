<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <span>试验站</span>
      </div>
      <div class="b">
        <el-tree :data="stations" highlight-current :props="treeProps" @node-click="changeItem">
        </el-tree>      
      </div>
    </div>
    <div class="box middle-box">
      <div class="h h1">
        <span>流程图</span>
      </div>
      <div class="b">
        <svg class="flowChart" @mouseup="drop" @mousemove="move" @click="contextmenuPositon=[-9999,-9999]">
          <ProcessSvg v-for="(item,index) in currentwfc.processes" :item="item" @grab="grab" @rightClick="contextmenu" :selected="processSelIndex==index">
          </ProcessSvg>
          <ArrowSvg :item="item" @grab="grabArrow" v-for="(item,index) in currentwfc.arrows" :selected="arrowSelIndex==index"></ArrowSvg>
        </svg>
        <ul :style="{left:contextmenuPositon[0] + 'px',top:contextmenuPositon[1] + 'px'}" class="contextmenu" @contextmenu.prevent>
          <li><a>编辑</a></li>
          <li><a @click="delProcess">删除</a></li>
        </ul>
      </div>
    </div>
    <div class="box right-box">
      <div class="h">
        <span>工作流组件</span>
      </div>
      <div class="b">
        <div class="func">
          <el-button :class="{choose:eventStatus==1}" @click="eventStatus = 1">对象</el-button>
          <el-button :class="{choose:eventStatus==2}" @click="eventStatus = 2">连接</el-button>
        </div>
        <svg class="wfcs" @mouseup="drop">
          <ProcessSvg :item="item" @grab="grab" v-for="item in wfcs"></ProcessSvg>
        </svg>
      </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'

import ProcessSvg from '@/components/ProcessSvg'
import ArrowSvg from '@/components/ArrowSvg'
export default {
  data() {
    return {
      contextmenuPositon: [-9999, -9999], //svg组件右键菜单位置
      cursePoint: [], //记录svg鼠标点击位置
      currentStation: {}, //当前站
      currentwfc: {}, //当前业务流程图
      eventStatus: 1, //事件状态, 1选中 ,2连接
      moveFlg: false, //处于移动状态
      processSelIndex: -1, //选中组件      
      arrowSelIndex: -1, //选中箭头
      newProcess: null,
      wfcs: [{
        id: null,
        processId: 1,
        label: '开始',
        point: [10, 20]
      }, {
        id: null,
        processId: 2,
        label: '业务受理',
        point: [10, 80]
      }, {
        id: null,
        processId: 3,
        label: '审核',
        point: [10, 140]
      }, {
        id: null,
        processId: 4,
        label: '任务分配',
        point: [10, 200]
      }, {
        id: null,
        processId: 5,
        label: '原始录入',
        point: [10, 260]
      }, {
        id: null,
        processId: 6,
        label: '报告编辑',
        point: [10, 320]
      }, {
        id: null,
        processId: 7,
        label: '归档',
        point: [10, 380]
      }],
      treeProps: {
        children: 'children',
        label: 'label',
        point: [10, 20]
      },
      stations: [{
        name: 'station1',
        label: '试验站1',
        processes: [{
          id: 1,
          processId: 1,
          label: '开始',
          parentIds: [],
          point: [10, 20]
        }, {
          id: 2,
          processId: 2,
          label: '业务受理',
          parentIds: [1],
          point: [10, 100]
        }]
      }, {
        name: 'station2',
        label: '试验站2',
        processes: []
      }, {
        name: 'station3',
        label: '试验站3',
        processes: []
      }]
    }
  },
  mounted() {
    window.onkeydown = e => {
      if (e.code == 'Delete') {
        if (this.processSelIndex != -1) {
          this.delProcess()
          this.processSelIndex = -1
        } else if (this.arrowSelIndex != -1) {
          let selArrow = this.currentwfc.arrows[this.arrowSelIndex]
          let wfc = this.currentwfc.processes.find(item => selArrow.id == item.id)
          let parentIdIndex = wfc.parentIds.indexOf(selArrow.parentId)
          wfc.parentIds.splice(parentIdIndex, 1)
          this.calcArrows()
          this.arrowSelIndex = -1
        }
      }
    }
  },
  components: { ProcessSvg, ArrowSvg },

  methods: {
    changeItem(data, node) {
      if (node && !node.isLeaf)
        return
      this.currentStation = data
      this.processSelIndex = -1
      this.arrowSelIndex = -1
      this.currentwfc = {
        processes: JSON.parse(JSON.stringify(data.processes)),
        arrows: []
      }
      this.calcArrows()
    },
    calcArrows() {
      this.currentwfc.arrows = []
      this.currentwfc.processes.map(item => {
        if (item.parentIds) {
          item.parentIds.map((parentId, index) => {
            let parentItem = this.currentwfc.processes.find(wfc => wfc.id == parentId)
            if (parentItem) {
              let startPoint, endPoint
              if (parentItem.point[1] + 38 < item.point[1]) {
                startPoint = [parentItem.point[0] + 75, parentItem.point[1] + 38]
                endPoint = [item.point[0] + 75, item.point[1]]
              } else if (parentItem.point[1] - 38 > item.point[1]) {
                startPoint = [parentItem.point[0] + 75, parentItem.point[1]]
                endPoint = [item.point[0] + 75, item.point[1] + 38]
              } else if (parentItem.point[0] + 150 < item.point[0]) {
                startPoint = [parentItem.point[0] + 150, parentItem.point[1] + 19]
                endPoint = [item.point[0], item.point[1] + 19]
              } else if (parentItem.point[0] - 150 > item.point[0]) {
                startPoint = [parentItem.point[0], parentItem.point[1] + 19]
                endPoint = [item.point[0] + 150, item.point[1] + 19]
              } else {
                startPoint = [parentItem.point[0], parentItem.point[1]]
                endPoint = [item.point[0], item.point[1]]
              }

              this.currentwfc.arrows.push({
                startPoint: startPoint,
                endPoint: endPoint,
                id: item.id,
                parentId: parentId
              })
            } else {
              item.parentIds.splice(index, 1)
            }
          })
        }
      })
    },
    contextmenu(e, item) {
      this.contextmenuPositon = [e.layerX, e.layerY]
    },
    grabArrow(e, item) {
      this.processSelIndex = -1
      this.currentwfc.arrows.forEach((subItem, index) => {
        if (subItem.id == item.id && subItem.parentId == item.parentId) {
          this.arrowSelIndex = index
        }
      })
    },
    grab(e, item) {
      if (this.arrowSelIndex != -1) { //当前为箭头
        this.arrowSelIndex = -1
      }
      if (item.id) { //已有svg
        let processIndex, processItem
        this.currentwfc.processes.forEach((subItem, index) => {
          if (subItem.id == item.id) {
            processIndex = index
            processItem = subItem
          }
        })
        if (this.eventStatus == 2) { //连接状态
          if (this.processSelIndex != -1) {
            if (this.processSelIndex != processIndex) { //进行连接
              let parentitem = this.currentwfc.processes[this.processSelIndex]
              if (processItem.parentIds.indexOf(parentitem.id) == -1 &&
                parentitem.parentIds.indexOf(processItem.id) == -1
              ) {
                processItem.parentIds.push(parentitem.id)
                this.calcArrows()
              }
            } else {
              this.processSelIndex = -1;
              return
            }
          }
        }
        this.processSelIndex = processIndex
      } else { //新建
        this.processSelIndex = -1;
        this.newProcess = item
        this.newProcess.parentIds = []
      }
      if (this.eventStatus == 1) {
        this.cursePoint = [e.layerX, e.layerY]
        this.moveFlg = true
      }
    },
    drop(e) {
      if (this.processSelIndex == -1) {
        this.newProcess = null
        return
      }
      if (this.moveFlg) {
        this.calcArrows()
        this.moveFlg = false
      }
    },
    delProcess() {
      if (this.processSelIndex != -1) {
        this.currentwfc.processes.splice(this.processSelIndex, 1)
        this.contextmenuPositon = [-9999, -9999]
        this.calcArrows()
        this.processSelIndex = -1
      }
    },
    move(e) {
      if (this.processSelIndex == -1 && !this.newProcess) {
        return
      }
      if (this.moveFlg) {
        let newCursePoint = [e.layerX, e.layerY]
        if (this.processSelIndex == -1) {
          if (this.currentwfc.processes.length) {
            this.newProcess.id = this.currentwfc.processes[this.currentwfc.processes.length - 1].id + 1
          } else {
            this.newProcess.id = 1
          }
          this.newProcess.point = [newCursePoint[0] - 50, newCursePoint[1] - 20]
          this.currentwfc.processes.push(JSON.parse(JSON.stringify(this.newProcess)))
          this.newProcess = null
          this.processSelIndex = this.currentwfc.processes.length - 1
        } else {
          let currentProcess = this.currentwfc.processes[this.processSelIndex]
          currentProcess.point = [currentProcess.point[0] + newCursePoint[0] - this.cursePoint[0], currentProcess.point[1] + newCursePoint[1] - this.cursePoint[1]]
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
}

.left-box {
  width: 20%;
  height: 100vh;
  float: left;
  overflow: auto;
}

.middle-box {
  width: 60%;
  height: 100vh;
  float: left;
}

.right-box {
  width: 20%;
  height: 100vh;
  float: left;
  overflow: auto;
}

.h1 {
  background-color: #ccc;
}

.flowChart {
  position: relative;
  width: 99%;
  height: 95vh;
}

.wfcs {
  position: relative;
  width: 99%;
  height: 80vh;
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
