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
.first-menu {
  height: 32px;
}

.first-menu>li {
  position: relative;
  float: left;
  width: 120px;
  overflow: hidden;
  border-right: 1px solid #ccc;
}

.first-menu>li>ul {
  position: absolute;
  left: -9999px;
}

.first-menu>li:hover>ul {
  display: block;
  position: relative;
  left: 0;
  border: 1px solid #ccc;
  z-index: 9999;
}

.first-menu>li,
.first-menu>li:hover>ul>li {
  background-color: #699;
  color: #fff;
  transition: all 0.5s;
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
}

.first-menu>li:hover,
.first-menu>li:hover>ul>li:hover {
  color: #000;
  background-color: #9cc;
}

@media screen and (max-width: 1000px) {
  .first-menu {
    height: auto;
    width: auto;
    border: 1px solid #ccc;
  }
  .first-menu>li {
    float: none;
    border-bottom: 1px solid #ccc;
    border-right: none;
    overflow: visible;
  }
  .first-menu>li:hover>ul {
    position: absolute;
    left: 120px;
    width: 120px;
    top: 0;
  }
}

</style>
