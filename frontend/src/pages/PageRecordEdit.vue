<template>
  <div>
    <h2>{{title}}</h2>
    <div class="box top-box">
      <div class="h">
        <span>检测仪器设备信息</span>
        <div class="right-btn">
          <button type="text" @click="addInstr"><i class="iconfont icon-plus"></i></button>
        </div>
      </div>
      <div class="b">
        <el-table :data="selectInstruments" border>
          <el-table-column type="index" width="60">
          </el-table-column>
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in instrFields">
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template scope="scope">
              <button @click="delRow(scope.row)" type="text"><i class="iconfont icon-trash"></i>
              </button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
    <div class="box bottom-box">
      <div class="h">
        <span>检测数据</span>
      </div>
      <div class="b">
        <TplRender :tpl="curTestItem.tpl" :attrs="curTestItem.attrs" v-model="curTestItem.data" v-if="curTestItem">
        </TplRender>
      </div>
    </div>
  </div>
</template>
<script>
import TplRender from '../components/TplRender'
import { mixObject } from '@/shared/util'
export default {
  name: 'PageRecordEdit',
  components: { TplRender },
  data() {
    return {
      proId: '',
      selectInstruments: [],
      title: '',
      curTestItem: null,
      instrFields: [{
        name: 'instr_name',
        caption: '仪器设备名称'
      }, {
        name: 'instr_type',
        caption: '型号/规格'
      }, {
        name: 'instr_code',
        caption: '设备编号'
      }, {
        name: 'instr_accuracy',
        caption: '准确度'
      }, {
        name: 'instr_expire',
        caption: '有效期'
      }]
    }
  },
  mounted() {
    this.proId = this.$route.query.id
    let itemName = this.$route.query['item-name']
    this.axios.get("test-items").then(response => {
      let testItem = response.data.find(item => item.name == itemName)
      if (testItem) {
        this.title = testItem['name_cn']
        if (testItem["records"]) {
          this.$set(this, 'curTestItem', {
            name: testItem['name'],
            tpl: testItem["records"][0]['tpl'],
            attrs: testItem["records"][0]['attrs'],
            data: []
          })
        }
      }
      return this.axios.get("projects/" + this.proId)
    }).then(response => {})
  },
  methods: {
    addInstr() {},
  }
}

</script>
<style scoped>
.top-box {
  width: 100%;
  height: 30%;
  margin-top: 50px;
}

.bottom-box {
  width: 100%;
  height: 70%;
}

</style>
