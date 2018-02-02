<template>
  <section class="todoList">
    <header>
      <input v-model="newTodo" @keyup.enter="addTodo">
    </header>
    <section class="main" v-show="todos.length">
      <ul class="todo-list">
        <li v-for="todo in todos" class="todo" :class="{ editing: todo == editedTodo }">
          <div class="view">
            <label @dblclick="editTodo(todo)">{{ todo }}</label>
            <button class="destroy" @click="removeTodo(todo)"></button>
          </div>
          <input class="edit" type="text" v-model="todo.title" v-todo-focus="todo == editedTodo" @blur="doneEdit(todo)" @keyup.enter="doneEdit(todo)" @keyup.esc="cancelEdit(todo)">
        </li>
      </ul>
    </section>
  </section>
</template>
<script>
export default {
  name: 'TodoList',
  data() {
    return {
      todos: [],
      newTodo: '',
      beforeEditCache:'',
      editedTodo: null,
      visibility: 'all'
    }
  },
  computed: {

  },
  methods: {
    addTodo: function() {
      var value = this.newTodo && this.newTodo.trim()
      if (!value) {
        return
      }
      this.todos.push(value)
      this.newTodo = ''
    },
    removeTodo: function(todo) {
      this.todos.splice(this.todos.indexOf(todo), 1)
    },
    editTodo: function(todo) {
      this.beforeEditCache = todo
      this.editedTodo = todo
    },
    doneEdit: function(todo) {
      if (!this.editedTodo) {
        return
      }
      this.editedTodo = null
      todo = todo.trim()
      if (!todo) {
        this.removeTodo(todo)
      }
    },
    cancelEdit: function(todo) {
      this.editedTodo = null
      todo = this.beforeEditCache
    },
  }
}

</script>
<style scoped>


</style>
