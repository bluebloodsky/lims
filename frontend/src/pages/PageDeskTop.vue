<template>
  <div>
    <div class="out-box">
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
        <a class="more">更多>></a>
      </div>
    </div>
    <div class="out-box">
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
  </div>
</template>
<script>
export default {
  name: 'PageDeskTop',
  data() {
    return {
      pre_url: '/home/',
      fields: [{
        name: 'order_info.order_client.name',
        caption: '客户名称'
      }, {
        name: 'order_info.order_content.order_code',
        caption: '委托单编号'
      }, {
        name: 'sample_info.sample_base_info.product_name',
        caption: '试品名称'
      }, {
        name: 'step.alias',
        caption: '当前环节'
      }],
      taskTodos: []
    }
  },
  computed: {
    taskTodos_list() {
      return this.taskTodos.map(item => {
        var todoItem = JSON.parse(JSON.stringify(item))
        return todoItem
      })
    }
  },
  methods: {
    todoClick(row) {
      let url = "process-handle"
      this.$store.commit('addTab', url)
      this.$router.push({ path: this.pre_url + url + "?id=" + row['_id']['$oid'] })
    }
  },
  mounted() {
    this.axios.get("projects").then(response => {
      this.taskTodos = response.data;
    })
  }
}

</script>
<style scoped>
.out-box{
  float: left;
  width: 50%;
  height: 600px;
}
.box {
  width: 96%;
  height: 100%;
  margin: 0 auto;
  border: solid 1px #D4D4D4;
}

.more {
  position: absolute;
  bottom: 10px;
  right: 10px;
}

@media screen and (max-width: 1000px){
  .out-box{
    width: 100%;
  }
}
</style>
