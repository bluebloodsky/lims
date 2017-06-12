<template>
  <div>
    <div class="h">
      <i class="mdi iconfont icon-config">
        </i>
      <span>密码修改</span>
      <div class="right-btn">
        <el-button type="text" @click="submit"><i class="iconfont icon-submit"></i></el-button>
      </div>
    </div>
    <div class="b">
      <el-form :form="passwordInfo.datas" label-width="150px" style="text-align:left;padding: 20px">
        <el-form-item v-for="item in passwordInfo.fields" :label="item.caption">
          <el-input type="password" v-model="passwordInfo.datas[item.name]"></el-input>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      passwordInfo: {
        fields: [{
          name: 'old_password',
          caption: '原密码'
        }, {
          name: 'new_password',
          caption: '新密码'
        }, {
          name: 'confirm_password',
          caption: '新密码确认'
        }],
        datas: {
          old_password: '',
          new_password: '',
          confirm_password: ''
        }
      }
    }
  }
}
</script>
