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
        <div class="flowChart" @drop="drop">
          <div v-for="process in currentStation.processes" class="wfc">
          </div>
        </div> 
      </div>
    </div>
    <div class="box right-box">
      <div class="h">
        <span>工作流组件</span>       
      </div>
      <div class="b">
        <div v-for="wfc in workflow_components" class="wfc" draggable="true" 
        data-name="aa" @dragstart="dragStart">{{wfc.label}}</div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
import Vue from 'vue'

export default {
  data() {
    return {      
      currentStation: {
       
      },
      workflow_components:[{
        processId:1,
        name:'aa',
        label:'业务受理'
      },{
        processId:2,
        name:'bb',
        label:'审核'
      },{
        processId:3,
        name:'cc',
        label:'下单'
      },{
        processId:4,
        name:'dd',
        label:'原始记录编制'
      }],
      treeProps: {
        children: 'children',
        label: 'label'
      },
      stations:[{
        name:'station1',
        label:'互感器质检站',
        processes:[{
          id : 1 ,
          processId : 1,
          parentId:[]         
        },{
          id: 2 ,
          processId: 2 ,
          parentId:[1]
        },{
          id: 3 ,
          processId: 3 ,
          parentId:[1]
        },{
          id: 4 ,
          processId: 4 ,
          parentId:[2,3]
        }]
      },{
        name:'station2',
        label:'变压器质检站'
      },{
        name:'station3',
        label:'避雷器质检站'
      }]
    }
  },  
  methods: {
    changeItem(data, node) {
      if (!node.isLeaf)
        return
      this.currentStation = data
    },
    dragStart(e){
      let componentName = e.target.getAttribute('data-name')
    },
    drop(e){

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

.flowChart{
  position: relative;
  width: 99%;
  height: 95vh;
}
.wfc{
  border: 1px solid #aaa;
  margin: 5px;
  background-color: #ccc;
  text-align: center;
  line-height: 30px;
  height: 30px;
  width: 200px;
}
</style>
