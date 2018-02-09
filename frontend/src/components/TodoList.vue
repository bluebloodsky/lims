<template>
  <section class="wrapper">
    <input class="new-todo" autofocus autocomplete="off" placeholder="添加一个新值" v-model="newTodo" @keyup.enter.stop="addTodo">
    <ul class="todo-list">
      <Todo v-model="todo.text" :key="todo.id" v-for="todo in todos" @deleteTodo="deleteTodo(todo)"></Todo>
    </ul>
  </section>
</template>
<script>
import Todo from './Todo'
export default {
  name: 'TodoList',
  props: {
    value: {
      type: Array,
      requied: true
    }
  },
  data() {
    return {
      newTodo: '',
      maxId:0,
      todos: []
    }
  },
  components: {
    Todo
  },
  watch: {
    todos: {
      handler(newVal) {
        let result = newVal.map(item => {
          return item.text
        })
        this.$emit('input', result)
      },
      deep: true
    }
  },
  computed: {},
  mounted() {
    if (this.value) {
      this.todos = this.value.map(val => {
        return {
          id: this.maxId++,
          text: val
        }
      })
    }
  },
  methods: {
    addTodo() {
      let value = this.newTodo && this.newTodo.trim()
      if (!value) {
        return
      }
      this.todos.push({
        id: this.maxId++,
        text: value,
      })
      this.newTodo = ''
    },   
    deleteTodo(todo) {
      this.todos.splice(this.todos.indexOf(todo), 1)
    }
  }
}

</script>
<style scoped>
.wrapper {
  border: 1px solid #aaa;
  padding: 10px;
}

.new-todo {
  display: block;
  padding: 10px;
}

.todo-list {
  margin-top: 10px;
  padding: 0;
  list-style: none;
}

</style>
