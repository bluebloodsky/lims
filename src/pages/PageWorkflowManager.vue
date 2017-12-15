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
      <div class="b" @keyup="keydown">
        <svg class="flowChart" @mouseup="drop" @mousemove="move" @click="contextmenuPositon=[-9999,-9999]">
          <ProcessSvg v-for="item in currentwfc.processes" :item="item" @grab="grab" @rightClick="contextmenu">
          </ProcessSvg>
          <ArrowSvg :item="item" v-for="item in currentwfc.arrows"></ArrowSvg>
          <rect :x="selectSvg[0]" :y="selectSvg[1]" :width="selectSvg[2]" :height="selectSvg[3]" stroke="#22C" fill="none" stroke-dasharray="5,5"></rect>
        </svg>
        <ul :style="{left:contextmenuPositon[0] + 'px',top:contextmenuPositon[1] + 'px'}" class="contextmenu" @contextmenu.prevent>
          <li><a>编辑</a></li>
          <li><a @click="delSvg">删除</a></li>
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
      currentSvg: null, //当前svg组件
      currentStation: {}, //当前站
      currentwfc: {}, //当前业务流程图
      eventStatus: 1, //事件状态, 1选中 ,2连接
      moveFlg: false, //处于移动状态
      wfcs: [{
        id: null,
        processId: 1,
        label: '业务受理',
        point: [10, 20]
      }, {
        id: null,
        processId: 2,
        label: '审核',
        point: [10, 80]
      }, {
        id: null,
        processId: 3,
        label: '下单',
        point: [10, 140]
      }, {
        id: null,
        processId: 4,
        label: '原始记录编制',
        point: [10, 200]
      }],
      treeProps: {
        children: 'children',
        label: 'label',
        point: [10, 20]
      },
      stations: [{
        name: 'station1',
        label: '互感器质检站',
        processes: [{
          id: 1,
          processId: 1,
          label: '业务受理',
          parentIds: [],
          point: [10, 20]
        }, {
          id: 2,
          processId: 2,
          label: '审核',
          parentIds: [1],
          point: [10, 100]
        }]
      }, {
        name: 'station2',
        label: '变压器质检站',
        processes: []
      }, {
        name: 'station3',
        label: '避雷器质检站',
        processes: []
      }]
    }
  },
  computed: {
    selectSvg() {
      if (this.currentSvg && this.currentSvg.id) {
        return [this.currentSvg.point[0] - 5, this.currentSvg.point[1] - 5, 160, 50]
      } else {
        return [-9999, -9999, 0, 0]
      }
    }
  },
  mounted() {
    window.onkeydown = e => {
      console.log(e)
      if (e.code == 'Delete') {
        if (this.currentSvg){
          this.delSvg()
        }
      }
    }
  },
  components: { ProcessSvg, ArrowSvg },
  methods: {
    changeItem(data, node) {
      if (!node.isLeaf)
        return
      this.currentStation = data
      this.currentSvg = null
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
                startPoint,
                endPoint
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
    grab(e, item) {
      if (item.id) { //已有svg
        let childSvg = this.currentwfc.processes.find(wfc => wfc.id == item.id)
        if (this.eventStatus == 2) { //连接状态
          if (this.currentSvg) {
            if (this.currentSvg.id != childSvg.id) { //进行连接
              if (childSvg.parentIds.indexOf(this.currentSvg.id) == -1 &&
                this.currentSvg.parentIds.indexOf(childSvg.id) == -1
              ) {
                childSvg.parentIds.push(this.currentSvg.id)
                this.calcArrows()
              }
            } else {
              this.currentSvg = null;
              return
            }
          }
        }
        this.currentSvg = childSvg
      } else if (this.eventStatus == 1) { //新建
        this.currentSvg = item
        this.currentSvg.parentIds = []
      }
      if (this.eventStatus == 1) {
        this.cursePoint = [e.layerX, e.layerY]
        this.moveFlg = true
      }
    },
    drop(e) {
      if (this.moveFlg) {
        this.calcArrows()
        this.moveFlg = false
      }
    },
    keydown(e) {
      console.log(e)
    },
    delSvg() {
      let index = this.currentwfc.processes.indexOf(this.currentSvg)
      if (index != -1) {
        this.currentSvg = null
        this.currentwfc.processes.splice(index, 1)
        this.contextmenuPositon = [-9999, -9999]
        this.calcArrows()
      }
    },
    move(e) {
      if (this.moveFlg) {
        let newCursePoint = [e.layerX, e.layerY]
        if (!this.currentSvg.id) { //新建svg  
          if (this.currentwfc.processes.length) {
            this.currentSvg.id = this.currentwfc.processes[this.currentwfc.processes.length - 1].id + 1
          } else {
            this.currentSvg.id = 1
          }
          this.currentSvg.point = [newCursePoint[0] - 50, newCursePoint[1] - 10]
          this.currentwfc.processes.push(this.currentSvg)
        } else {
          this.currentSvg.point = [this.currentSvg.point[0] + newCursePoint[0] - this.cursePoint[0], this.currentSvg.point[1] + newCursePoint[1] - this.cursePoint[1]]
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
  height: 50vh;
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
