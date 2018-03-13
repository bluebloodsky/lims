<template>
  <section class="wrapper">
    <input class="new-todo" autofocus autocomplete="off" placeholder="" v-model="newTodo" @keyup.enter.stop="addTodo">
    <table>
      <tr v-for="(todo,index) in todos">
        <td>{{todo.text}}</td>
        <td>
          <PictureCard v-model="todo.files"></PictureCard>
        </td>
        <td>
          <button class="destroy" @click="deleteTodo(index)"></button>
        </td>
      </tr>
    </table>
  </section>
</template>
<script>
import PictureCard from './PictureCard'
export default {
  name: 'PictureList',
  components: {
    PictureCard
  },
  props: {
    value: {
      requied: true
    }
  },
  data() {
    return {
      newTodo: '',
      todos: [],
    }
  },
  watch: {
    todos: {
      handler(newVal) {
        this.$emit('input', newVal)
      },
      deep: true
    }
  },
  computed: {},
  mounted() {
    if (this.value) {
      this.todos = this.value
    }
  },
  methods: {
    addTodo() {
      let txt = this.newTodo && this.newTodo.trim()
      if (!txt) {
        return
      }
      this.todos.push({
        text: txt,
        files: []
      })
      this.newTodo = ''
    },
    deleteTodo(index) {
      if (this.todos[index]["files"]) {
        let urls = this.todos[index]["files"].map(file => file.url)
        this.axios.delete('files/' + urls.concat(","))
      }
      this.todos.splice(index, 1)
    },
  }
}

</script>
<style scoped>
.destroy {
  font-size: 30px;
  border: none;
  background: transparent;
  color: #cc9a9a;
}

.destroy:hover {
  color: #af5b5e;
}

.destroy:before{
  content:'×'
}
table {
  width: 100%;
  border: 1px solid #ccc;
  border-collapse: collapse;
  text-align: center;
  margin-top: 2px;
}

td,
th {
  border: 1px solid #ccc;
}
</style>
