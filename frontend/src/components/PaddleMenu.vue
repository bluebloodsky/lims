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
  height: 21px;
}

.first-menu>li {
  position: relative;
  float: left;
  width: 128px;
  background-color: #55819D;
  color: #fff;
  border-left: 1px solid #D4D4D4;
  font-size: 16px;
  transition: background-color 0.2s;
  text-align: center;
}

.first-menu>li>ul {
  position: absolute;
  width: 170px;
  background-color: #fff;
  border: 1px solid #D4D4D4;
  border-top: none;
  left: -9999px;
  padding: 10px;
}

.first-menu>li:hover>ul {
  position: relative;
  left: -1px;
  z-index: 999;
}

.first-menu>li:hover>ul>li {
  text-align: left;
  transition: background-color 0.2s;
  border-bottom: 1px solid #ccc;
  font-size: 14px;
  font-weight: normal;
  background-color: #fff;
  color: #484848
}

.first-menu>li:hover>ul>li:last-child {
  border-bottom: none;
}

.first-menu>li>a,
.first-menu>li>span,
.first-menu>li:hover>ul>li>a {
  height: 28px;
  line-height: 28px;
  display: block;
}

.first-menu>li:hover {
  background-color: #fff;
  color: #484848
}


.first-menu>li:hover>ul>li:hover {
  background-color: #55819D;
  color: #fff;
}

@media screen and (max-width: 1100px) {
  .first-menu {
    height: auto;
    width: auto;
    border: 1px solid #D4D4D4;
  }
  .first-menu>li {
    float: none;
    border-bottom: 1px solid #ccc;
    border-right: none;
    overflow: visible;
  }
  .first-menu>li:hover>ul {
    position: absolute;
    left: 128px;
    top: 0;
    border: 1px solid #D4D4D4;
  }
}

</style>
