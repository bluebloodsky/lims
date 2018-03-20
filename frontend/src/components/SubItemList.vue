<template>
  <section class="wrapper">
    <input class="new-todo" autofocus autocomplete="off" placeholder="" v-model="newTodo" @keyup.enter.stop="addTodo">
    <div style="width: 100%;border: 1px solid #ececec; border-collapse: collapse;text-align:left">
      <div v-for="(subItem,index) in subItems">
          <input v-model="subItem.name">
          <input v-model="subItem.name_cn">
          <button class="destroy" @mouseup.left="deleteTodo(index)">×</button>
      </div>
    </div>
  </section>
</template>
<script>
import {copyObject} from '@/shared/util'
export default {
  name: 'SubItemList',
  props: {
    value: {
      required: true
    }
  },
  data() {
    return {
      newTodo: '',
      subItems: [],
    }
  },
  watch: {
    subItems: {
      handler(newVal) {
        this.$emit('input', newVal)
      },
      deep: true
    },
    value : function(){
      this.subItems = this.value
    }
  },
  methods: {
    addTodo() {
      let txt = this.newTodo && this.newTodo.trim()
      if (!txt) {
        return
      }
      this.subItems.push({
        name: txt,
        name_cn: ''
      })
      this.newTodo = ''
    },
    deleteTodo(index) {     
      this.subItems.splice(index, 1)
    },
  }
}

</script>
<style scoped>
.wrapper {
  border: 1px solid #aaa;
  padding: 10px;
}

.destroy {
  font-size: 30px;
  border: none;
  background: transparent;
  color: #cc9a9a;
}

.destroy:hover {
  color: #af5b5e;
}

</style>
