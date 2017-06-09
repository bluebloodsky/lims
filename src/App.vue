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
      title: '智能设备在线监测系统',
    }
  }
}
</script>
<style>
@import "assets/icon-font/iconfont.css";
@import "assets/base.css";
</style>
