<template>
  <ul class="first-menu">
    <li v-for="item in datas">
      <router-link :to="item.url" v-if="item.url">{{item.name}}</router-link>
      <span v-else>{{item.name}}</span>
      <ul v-if="item.items" class="second-menu">
        <li v-for="subitem in item.items">
          <router-link :to="subitem.url" v-if="subitem.url">{{subitem.name}}</router-link>
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
    }
  }
}
</script>
<style scoped>
.first-menu {
  background-color: #58a;
  height: 33px;
  float: left;
  width: 100%;
}

.first-menu > li {
  position: relative;
  float: left;
  width: 121px;
  overflow: hidden;
}

.first-menu > li > a,
.first-menu > li > span {
  width: 120px;
  border-right: 1px solid #ccc;
}

.first-menu > li > ul {
  position: absolute;
  left: -9999px;
}

.first-menu > li:hover > ul {
  display: block;
  position: relative;
  float: left;
  left: 0;
  border: 1px solid #ccc;
  z-index: 9999;
}

.first-menu > li:hover > ul > li {
  background: #58a;
}

.first-menu > li:hover > ul > li > a {
  min-width: 120px;
  border-bottom: 1px solid #ccc;
}

.first-menu > li > a,
.first-menu > li > span,
.first-menu > li:hover > ul > li > a {
  height: 33px;
  line-height: 33px;
  text-align: center;
  display: block;
  color: #fff;
}

.first-menu > li:hover,
.first-menu > li:hover > ul > li:hover {
  background-color: #d1e3ef;
}

.first-menu > li:hover > a,
.first-menu > li:hover > span,
.first-menu > li:hover > ul > li:hover > a {
  color: #01301E;
}
</style>
