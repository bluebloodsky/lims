<template>
  <li class="todo" :class="{editing: editing }">
    <div class="view">
      <label v-text="value" @dblclick="editing = true"></label>
      <button class="destroy" @mouseup.left="deleteTodo"></button>
    </div>
    <input class="edit" v-show="editing" v-focus="editing" :value="value" @keyup.enter="doneEdit" @keyup.esc="cancelEdit" @blur="doneEdit">
  </li>
</template>
<script>
export default {
  name: 'Todo',
  props: {
    value: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      editing: false
    }
  },
  directives: {
    focus(el, { value }, { context }) {
      if (value) {
        context.$nextTick(() => {
          el.focus()
        })
      }
    }
  },
  methods: {
    doneEdit(e) {
      const val = e.target.value.trim()
      this.$emit('input', val)
      this.editing = false
    },
    cancelEdit(e) {
      e.target.value = this.value
      this.editing = false
    },
    deleteTodo(e) {
      this.$emit('deleteTodo')
    }
  }
}

</script>
<style scoped>
.todo {
  position: relative;
  border-bottom: 1px solid #ededed;
}

.editing {
  border-bottom: none;
  padding: 0;
}

.editing .edit {
  display: block;
  border-radius: 4px;
  padding: 14px 2px;
}

.editing .view {
  display: none;
}

label {
  word-break: break-all;
  padding: 15px 2px;
  display: block;
  line-height: 1.2;
  transition: color 0.4s;
}

.edit {
  display: none;
}


.destroy {
  display: none;
  position: absolute;
  top: 0;
  right: 10px;
  bottom: 0;
  width: 40px;
  height: 40px;
  margin: auto 0;
  font-size: 30px;
  border: none;
  background: transparent;
  color: #cc9a9a;
}

.destroy:hover {
  color: #af5b5e;
}

.destroy:after {
  content: '×';
}

li:hover .destroy {
  display: block;
}
</style>
