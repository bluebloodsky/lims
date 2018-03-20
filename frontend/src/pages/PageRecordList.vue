<template>
  <div>
    <h2>检测项目汇总表</h2>
    <el-table :data="testItems" border @row-click="rowClick">
      <el-table-column type="index" width="60">
      </el-table-column>
      <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
export default {
  name: 'PageRecordList',
  data() {
    return {
      proId: '',
      currentProject: null,
      fields: [{
        name: 'item_desc',
        caption: '检测项目'
      }, {
        name: 'item_result',
        caption: '检测结果'
      }]
    }
  },
  created() {

    this.axios.get("test-items").then(response => {

      return this.axios.get("projects/" + this.$route.query.id)
    }).then(response => {
      this.currentProject = response.data

    })
  },
  computed: {
    testItems() {
      let items = []
      for (let test in this.currentProject.test_info) {
        items.push(test)
      }
      return items
    }
  },
  methods: {
    rowClick() {}
  }
}

</script>
<style scoped>


</style>
