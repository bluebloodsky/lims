<template>
  <div>
    <div class="box">
      <div class="h">
        <span>待办业务</span>
      </div>
      <div class="b">
        <el-table :data="taskTodos_list" border @row-click="todoClick">
          <el-table-column type="index" width="60">
          </el-table-column>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
          </el-table-column>
        </el-table>
      </div>
      <a>更多>></a>
    </div>
    <div class="box">
      <div class="h">
        <span>我的日程</span>
      </div>
      <div class="b" id="mychart">
        <!--
       <full-calendar :events="fcEvents" :locale="de"></full-calendar>
     -->
      </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'
import echarts from 'echarts'
import Qs from 'qs'
export default {
  name:'desktop',
  data() {
    return {
      fcEvents: [{
        title: '局放试验',
        start: '2017-08-25',
        end: '2017-08-27'
      }],
      fields: [{
        name: 'task_code',
        caption: '任务编号'
      }, {
        name: 'del_code',
        caption: '委托单编号'
      }, {
        name: 'task_type_cn',
        caption: '任务类型'
      }, {
        name: 'task_step_cn',
        caption: '当前环节'
      }],
      task_todo: [{
        id: 1,
        task_code: '21',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }, {
        id: 2,
        task_code: '51',
        del_code: '22',
        task_type: '1',
        task_step: '2'
      }, {
        id: 3,
        task_code: '31',
        del_code: '22',
        task_type: '1',
        task_step: '3'
      }, {
        id: 4,
        task_code: '41',
        del_code: '22',
        task_type: '1',
        task_step: '4'
      }, {
        id: 5,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '5'
      }, {
        id: 6,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '6'
      }, {
        id: 7,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '7'
      }, {
        id: 8,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '8'
      }, {
        id: 9,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '9'
      }, {
        id: 10,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }],
      task_dones: [{
        id: 1,
        task_code: '21',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }, {
        id: 2,
        task_code: '51',
        del_code: '22',
        task_type: '1',
        task_step: '2'
      }, {
        id: 3,
        task_code: '31',
        del_code: '22',
        task_type: '1',
        task_step: '3'
      }, {
        id: 4,
        task_code: '41',
        del_code: '22',
        task_type: '1',
        task_step: '4'
      }, {
        id: 5,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '5'
      }, {
        id: 6,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '6'
      }, {
        id: 7,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '7'
      }, {
        id: 8,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '8'
      }, {
        id: 9,
        task_code: '11',
        del_code: '22',
        task_type: '2',
        task_step: '9'
      }, {
        id: 10,
        task_code: '11',
        del_code: '22',
        task_type: '1',
        task_step: '1'
      }]
    }
  },
  computed: {
    ...mapGetters({
      baseAttrs: 'baseAttrs',
      tabs: 'tabs'
    }),
    taskTodos_list() {
      return this.task_todo.map(item => {
        var item_list = JSON.parse(JSON.stringify(item))
        item_list.task_type_cn = this.baseAttrs.task_type[item.task_type]
        item_list.task_step_cn = this.baseAttrs.task_step[item.task_step]
        return item_list
      })
    },
    taskDones_list() {
      return this.task_dones.map(item => {
        var item_list = JSON.parse(JSON.stringify(item))
        item_list.task_type_cn = this.baseAttrs.task_type[item.task_type]
        item_list.task_step_cn = this.baseAttrs.task_step[item.task_step]
        return item_list
      })
    }
  },
  methods: {
    todoClick(row) {
      this.$store.commit('addTab', { name: '流程处理', url: 'pro_handle' , component:'PageProHandle' })
      this.$router.push({
        path: '/home/pro_handle',
        query:{id:row.id}
      })
    }
  },
  mounted() {
  }
}

</script>
<style scoped>
.box {
  position: relative;
  width: 48%;
  height: 600px;
  margin: 1%;
  float: left;
  border: solid 1px #D4D4D4;
}

a {
  position: absolute;
  bottom: 10px;
  right: 10px;
}

</style>
