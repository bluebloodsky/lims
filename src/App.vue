<template>
  <div id="app">
    <router-view :title="title"></router-view>
  </div>
</template>
<script>
export default {
  name: 'app',
  data() {
    return {
      title: '武汉南瑞实验室信息管理系统',
    }
  }
}
</script>
<style>
@import "assets/icon-font/iconfont.css";
@import "assets/base.css";
</style>
