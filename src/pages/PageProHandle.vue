<template>
  <div class="box">
    <div class="h">
      <span>"{{currentTask.task_code}}"的业务办理流程</span>
      <a> <<回退 </a>
      <a> >>移交 </a>
    </div>
    <div class="b">
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      currentTask: {}
    }
  },
  mounted() {
    this.currentTask = this.$tab.params.task
  }
}

</script>
<style scoped></style>
