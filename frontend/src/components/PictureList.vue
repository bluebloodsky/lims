<template>
  <section>
    <input class="new-todo" autofocus autocomplete="off" placeholder="" v-model="newTodo" @keyup.enter.stop="addTodo">
    <table border style="width: 100%;border: 1px solid #ececec; border-collapse: collapse;text-align:left">
      <tr v-for="(todo,index) in todos">
        <td>{{todo.text}}</td>
        <td>
          <PictureCard v-model="todo.files"></PictureCard>
        </td>
        <td>
          <button class="destroy" @click="deleteTodo(index)">×</button>
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
        files: [{ name: 'food.jpeg', url: '0e0fdc55f6946560f0fe144df0783e4a.jpg' }]
      })
      this.newTodo = ''
    },
    deleteTodo(index) {
      if (this.todos[index]["files"]) {

      }
      this.todos.splice(index, 1)
    },
  }
}

</script>
<style>
.destroy {
  font-size: 30px;
  border: none;
  background: transparent;
  color: #cc9a9a;
}

.destroy:hover {
  color: #af5b5e;
}

.el-upload-list--picture-card .el-upload-list__item,
.el-upload--picture-card {
  width: 80px;
  height: 80px;
  line-height: 80px;
}

</style>
