<template>
  <div class="hello">
  <div v-for="item in items">{{item.sen_id}}</div>
  </div>
</template>
<script>
export default {
  name: 'hello',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      items: []
    }
  },
  created () {
    this.$http.get('http://localhost:8088/web-arm/api/sensors').then(m => {
      this.items = m.data
    })
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1,
h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}
</style>
