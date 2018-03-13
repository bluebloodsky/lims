<template>
  <ul class="first-menu">
    <li v-for="item in datas">
      <a v-if="item.url" @click="callback(item.url)">{{item.name}}</a>
      <span v-else>{{item.name}}</span>
      <ul v-if="item.items">
        <li v-for="subitem in item.items">
          <a v-if="subitem.url" @click="callback(subitem.url)">{{subitem.name}}</a>
        </li>
      </ul>
    </li>
  </ul>
</template>
<script>
export default {
  props: {
    datas: {
      type: Array
    },
    callback: {
      type: Function
    }
  },
  created() {},
  methods: {}
}

</script>
<style scoped>
.first-menu{
  height: 32px;
}
.first-menu>li {
  position: relative;
  float: left;
  width: 120px;
  overflow: hidden;
}

.first-menu>li>a,
.first-menu>li>span {
  width: 118px;
  border-right: 1px solid #ccc;
}

.first-menu>li>ul {
  position: absolute;
  left: -9999px;
}

.first-menu>li:hover>ul {
  display: block;
  position: relative;
  float: left;
  left: 0;
  border: 1px solid #ccc;
  z-index: 9999;
}

.first-menu>li:hover>ul>li {
  background: #0097a7;
}

.first-menu>li:hover>ul>li>a {
  min-width: 120px;
  border-bottom: 1px solid #ccc;
}

.first-menu>li>a,
.first-menu>li>span,
.first-menu>li:hover>ul>li>a {
  height: 32px;
  line-height: 32px;
  text-align: center;
  display: block;
  color: #fff;
}

.first-menu>li:hover,
.first-menu>li:hover>ul>li:hover {
  background-color: #0cc;
}

</style>
