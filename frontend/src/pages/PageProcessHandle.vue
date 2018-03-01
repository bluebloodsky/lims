<template>
  <div class="box">
    <div class="h">
      <span v-if="currentProject&&currentProject.order_info&&currentProject.order_info">
      "{{currentProject.order_info.order_content.order_code}}"</span>的业务办理流程
      <a> <<回退 </a>
      <a> >>移交 </a>
    </div>
    <div class="b">
      <ul class="tabs">
        <li>
          <a :class="{active:activeTab==tabIndex}" @click="activeTab=tabIndex" v-for="(tabName,tabIndex) in tabPages">{{tabName}}</a>
        </li>
      </ul>
      <div v-show="activeTab == 0">
        <el-table>
        </el-table>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'PageProcessHandle',
  props: {
    closeCurrentTab: {
      type: Function
    }
  },
  data() {
    return {
      tabPages: ["办理过程", "流程图"],
      activeTab: 0,
      currentProject: {}
    }
  },
  mounted() {
    if(!this.$route.query.id){
      this.closeCurrentTab()
      return
    }
    this.axios.get("projects/" + this.$route.query.id).then(response => {
      this.currentProject = response.data
    })
  }
}

</script>
<style scoped>


</style>
