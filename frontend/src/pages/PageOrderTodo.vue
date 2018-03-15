<template>
  <div>
    <div v-for="color in colors" :style="{'background-color':color.bg_color , 'color':color.ft_color}">{{color.bg_color}}</div>
  </div>
</template>
<script>
export default {
  name: 'PageOrderTodo',
  data() {
    return {
      colors: [],
    }
  },
  created() {
    let _vals = ['0', '3', '6', '9', 'c', 'f']
    let _len = 6
    for (let i = 0 ;  i < _len ; i++) {
      for (let j = 0 ;  j < _len ; j++) {
        for (let k = 0 ;  k < _len ; k++) {
          this.colors.push({
            bg_color:'#' +_vals[i] + _vals[j] + _vals[k] ,
            ft_color:'#' + _vals[_len-1-i] + _vals[_len-1-j] + _vals[_len-1-k]
          })
        }
      }
    }
  }
}

</script>
<style scoped>
div>div {
  margin: 10px;
  width: 50px;
  height: 50px;
  line-height: 50px;
  float:left;
}

</style>
