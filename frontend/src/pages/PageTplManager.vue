<template>
  <div>
    <div class="box left-box">
      <div class="h">
        <span>项目</span>
      </div>
      <div class="b">
        <el-tree :data="testItemAttrs" highlight-current :props="treeProps" @node-click="changeItem">
        </el-tree>
      </div>
    </div>
    <div class="box middle-box">
      <div class="h h1">
        <div class="right-btn">
          <el-button type="text" @click=""><i class="iconfont icon-submit"></i></el-button>
          <el-button type="text" @click="mode=1-mode"><i class="iconfont icon-edit"></i></el-button>
        </div>
      </div>
      <div class="b">
        <div v-if="mode==1">
          <TempTestItemParam :tpl="currentItem.tpl">
            <template v-for="param in currentItem.params">
              <input v-if="param.type=='input'" :slot="param.name" :disabled="param.readonly">
              <input v-else-if="param.type=='radio'||param.type=='checkbox'" :disabled="param.readonly" :type="param.type" :name="param.name" :slot="param.name+'_'+opt_key" :value="opt_key" v-for="(option,opt_key) in baseAttrs[param.options]">
              <select v-else-if="param.type=='select'" :disabled="param.readonly" :slot="param.name">
                <option v-for="option in baseAttrs[param.options]">
                  {{option}}
                </option>
              </select>
              <table v-if="param.type=='table'" :slot="param.name" :disabled="param.readonly" class="dataintable" border="1">
                <tbody>
                  <tr>
                    <th v-for="col in param.cols">{{col.label}}</th>
                    <th><button>+</button></th>
                  </tr>
                  <tr v-for="row in [1,2,3]">
                    <td v-for="col in param.cols">
                      <input type="" name="" :disabled="col.readonly" :class="{disabled:col.readonly}">
                    </td>
                     <td style="text-align:center"><button>-</button></td>
                  </tr>
                </tbody>
              </table>
            </template>
          </TempTestItemParam>
        </div>
        <div v-else>
          <textarea v-model="currentItem.tpl"></textarea>
        </div>
      </div>
    </div>
    <div class="box right-box">
      <div class="h">
        <span>项目参数</span>
        <div class="right-btn">
          <el-button type="text" @click=""><i class="iconfont icon-plus"></i></el-button>
        </div>
      </div>
      <div class="b">
        <el-collapse>
          <el-collapse-item :title="param.label + '/' + param.name" v-for="param in currentItem.params">
            <el-form label-width="80px" label-position="left">
              <el-form-item label="名称">
                <el-input v-model="param.name"></el-input>
              </el-form-item>
              <el-form-item label="描述">
                <el-input v-model="param.label"></el-input>
              </el-form-item>
              <el-form-item label="类型">
                <el-select v-model="param.type" label="类型">
                  <el-option label="文本输入框" value="input"></el-option>
                  <el-option label="下拉框" value="select"></el-option>
                  <el-option label="单选按钮" value="radio"></el-option>
                  <el-option label="多选" value="checkbox"></el-option>
                  <el-option label="表格" value="table"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="选项" v-if="param.type=='select'||param.type=='radio'||param.type=='checkbox'">
                <el-select v-model="param.options" label="基本数据类型">
                  <el-option :label="key" :value="key" v-for="(option,key) in baseAttrs"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="只读">
                <el-switch v-model="param.readonly"></el-switch>
              </el-form-item>
            </el-form>
            <div style="text-align:center">
              <el-button v-if="param.type=='table'" @click="">编辑列</el-button>
              <el-button @click="">提交</el-button>
              <el-button @click="">删除</el-button>
            </div>
          </el-collapse-item>
        </el-collapse>
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
var TempTestItemParam = {
  props: ['item_name', 'tpl'],
  created() {
    this.$options.template = '<div>' + this.tpl + '</div>'
  }
}

export default {
  data() {
    return {
      mode: 0,
      currentItem: {
        tpl: '',
        params: []
      },
      treeProps: {
        children: 'children',
        label: 'label'
      }
    }
  },
  computed: {
    ...mapGetters({
      baseAttrs: 'baseAttrs',
      testItemAttrs: 'testItemAttrs'
    }),
  },
  components: { TempTestItemParam },
  methods: {
    changeItem(data, node) {
      if (!node.isLeaf)
        return
      this.currentItem = data
      let oldMod = this.mode
      this.mode = 0
      if (this.mode != oldMod) {
        setTimeout(() => {
          this.mode = oldMod
        }, 0)
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

textarea {
  position: relative;
  width: 99%;
  height: 95vh;
  font-size: 16px;
}

table.dataintable {
  margin-top: 15px;
  border-collapse: collapse;
  border: 1px solid #aaa;
  width: 100%;
  border-spacing: 0px;
}
td input {
  margin: -1px;
  padding: 0;
  height: 25px;
  width: 99%;
  font-size: 18px;
}

.disabled{  
  background-color: rgb(235, 235, 228);
}

</style>
