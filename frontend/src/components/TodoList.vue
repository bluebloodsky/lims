<template>
  <section class="wrapper">
    <input class="new-todo" autofocus autocomplete="off" placeholder="添加一个新值" v-model="newTodo" @keyup.enter.stop="addTodo">
    <ul class="todo-list">
      <Todo v-model="todo.text" :key="todo.id" v-for="todo in todos" @deleteTodo="deleteTodo(todo)"></Todo>
    </ul>
  </section>
</template>
<script>
import { copyObject ,remove} from '@/shared/util'
import Todo from './Todo'
export default {
  name: 'TodoList',
  props: {
    value: {
      required: true
    }
  },
  data() {
    return {
      newTodo: '',
      maxId: 0,
    }
  },
  components: {
    Todo
  },
  computed: {
    todos: {
      get() {
        if (this.value) {
          return this.value.map(val => {
            return {
              id: this.maxId++,
              text: val
            }
          })
        }
      },
      set(newVal) {
        let result = newVal.map(item => {
          return item.text
        })
        this.$emit('input', result)
      }
    }
  },
  methods: {
    addTodo() {
      let value = this.newTodo && this.newTodo.trim()
      if (!value) {
        return
      }
      let _todos = copyObject(this.todos)
      _todos.push({
        id: this.maxId++,
        text: value,
      })
      this.newTodo = ''
      this.todos = _todos
    },
    deleteTodo(todo) {
      let _todos = copyObject(this.todos)
      remove(_todos , todo)
      this.todos = _todos
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
