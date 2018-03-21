<template>
  <div>
    <h2>检测项目汇总表</h2>
    <el-table :data="testItems" border @row-click="rowClick">
      <el-table-column type="index" width="60">
      </el-table-column>
      <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields" :formatter="cellFormatter">
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
import Qs from 'qs'
export default {
  name: 'PageRecordList',
  data() {
    return {
      proId: '',
      pre_url: '/home/',
      currentProject: null,
      allItmes: [],
      fields: [{
        name: 'item_desc',
        caption: '检测项目'
      }, {
        name: 'item_result',
        caption: '状态'
      }]
    }
  },
  created() {
    this.proId = this.$route.query.id
    this.axios.get("test-items").then(response => {
      this.allItmes = response.data
      return this.axios.get("projects/" + this.proId)
    }).then(response => {
      this.currentProject = response.data
    })
  },
  computed: {
    testItems() {
      let items = []
      if (this.currentProject && this.currentProject.test_info) {
        for (let test in this.currentProject.test_info) {
          let testItem = this.allItmes.find(item => item.name == test)
          items.push({
            item_name: testItem.name,
            item_desc: testItem.name_cn,
            item_result: this.currentProject.test_info[test].status ? true : false
          })
        }
      }
      return items
    }
  },
  methods: {
    rowClick(row) {
      let option = {
        'id' : this.proId ,
        'item-name' :row['item_name']
      }
      let url = "record-edit?" + Qs.stringify(option)
      this.$store.commit('addTab', url)
      this.$router.push({ path: this.pre_url + url })
    },
    cellFormatter(row, column, cellValue) {
      if (Array.isArray(cellValue)) {
        return cellValue.join('/')
      } else if (typeof cellValue == 'boolean') {
        return cellValue ? '已检测' : '待检测'
      }
      return cellValue
    },
  }
}

</script>
<style scoped>


</style>
