<template>
  <div>
    <slot></slot>
    <ul>
      <li v-for="(content,key) in contents">
        <div v-if="content.type" :class="content.type">
          {{key}}:
          <CompContent :content="content.oldvalue" v-if="content.oldvalue"></CompContent>
          <span v-if="content.type=='change'" style="color:red">
          ==>
        </span>
          <CompContent :content="content.newvalue" v-if="content.newvalue"></CompContent>
        </div>
        <LogContents :contents="content" v-else>
          <span>【{{key}}】</span>
        </LogContents>
      </li>
    </ul>
  </div>
</template>
<script>
var CompContent = {
  template: `
  <span v-if="typeof content != 'object'">{{content}}</span>
  <ul v-else>
  <li v-for="(val,key) in content"> {{key}} -> {{val}}</li>
  </ul>
  `,
  props: ['content']
}
export default {
  name: 'LogContents',
  props: ['contents'],
  components: { CompContent }
}

</script>
<style scoped>
ul {
  margin-left: 1em;
}

li {
  border-left: #ccc 1px solid;
  border-top: #ccc 1px solid;
}

</style>
