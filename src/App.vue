<template>
  <div id="app">
    <PageHome :title="title"></PageHome>
  </div>
</template>
<script>
import PageHome from './pages/PageHome'
export default {
  name: 'app',
  data() {
    return {
      title: '武汉南瑞实验室信息管理系统',
    }
  },
  components: {
    PageHome
  }
}
</script>
<style>
@import "assets/icon-font/iconfont.css";
@import "assets/base.css";
</style>
