<template>
  <div>
    <div class="box">
      <div class="h">
        <span>试验项目</span>
        <div class="right-btn">
          <button type="text" @click="addItem"><i class="iconfont icon-plus"></i></button>
          <button type="text" @click="submit"><i class="iconfont icon-submit"></i></button>
        </div>
      </div>
      <div class="b">
        <el-table border :data="testItems">
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields" :formatter="cellFormatter">
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template scope="scope">
              <button @click="editRow(scope.row)" type="text"><i class="iconfont icon-edit"></i>
              </button>
              <button @click="delRow(scope.row)" type="text"><i class="iconfont icon-trash"></i>
              </button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
    <div class="right-pad-box" :class="{ 'show-box':flg_showRightBox}">
      <div class="h">
        <span>试验项目编辑</span>
        <div class="right-btn">
          <button type="text" @click="hideDetail"><i class="iconfont icon-cancel"></i></button>
        </div>
      </div>
      <div class="b">
        <el-form label-width="120px"  style="padding:15px 5px;"  onsubmit="return false;">
          <el-form-item label="名称">
            <el-input v-model="currentRow.name"></el-input>
          </el-form-item>
          <el-form-item label="描述">
            <el-input v-model="currentRow.name_cn"></el-input>
          </el-form-item>
          <el-form-item label="参数子项">
            <SubItemList v-model="currentRow.params"></SubItemList>
          </el-form-item>
          <el-form-item label="记录子项">
            <SubItemList v-model="currentRow.records"></SubItemList>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import SubItemList from '../components/SubItemList'
import { remove } from '@/shared/util'
export default {
  name: 'PageTestItemConfig',
  components: { SubItemList },
  data() {
    return {
      fields: [{
        name: 'name',
        caption: '项目名称'
      }, {
        name: 'name_cn',
        caption: '描述'
      }, {
        name: 'params',
        caption: '参数子项'
      }, {
        name: 'records',
        caption: '记录子项'
      }],
      flg_showRightBox: false,
      currentRow: {},
      testItems: []
    }
  },
  mounted() {
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
    cellFormatter(row, column, cellValue) {
      if (Array.isArray(cellValue)) {
        return cellValue.map(item => item.name_cn).join('/')
      } else {
        return cellValue
      }
    },
    editRow(row) {
      this.flg_showRightBox = true
      this.currentRow = row
    },
    delRow(row) {
      remove(this.testItems, row)
    },
    hideDetail() {
      this.flg_showRightBox = false
    },
    addItem() {
      this.currentRow = {
        name: '',
        name_cn: ''
      }
      this.testItems.push(this.currentRow)
      this.flg_showRightBox = true
    },
    submit() {
      this.axios.post("test-items", JSON.stringify(this.testItems)).then(response => {
        if (response.data['data']) {
          this.testItems = response.data['data']
        }
        this.$message({
          message: response.data['message'],
          type: 'success'
        });
      }).catch(err => {
        console.log("err:" + err)
      })
    }
  }

}

</script>
<style scoped>
.box{
  height: 100%;
  width: 100%;
}
.show-box{
  width: 40%;
}

</style>
