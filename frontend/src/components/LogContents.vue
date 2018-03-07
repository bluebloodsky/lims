<template>
  <ul>
    <li v-for="(content,key) in contents">
      【{{key}}】
      <span v-if="content.type" :class="content.type">
      	{{content.oldvalue}}
        <span v-if="content.type=='change'" style="color:red">
          ==>
        </span> {{content.newvalue}}
      </span>
      <LogContents :contents="content" v-else></LogContents>
    </li>
  </ul>
</template>
<script>
export default {
  name: 'LogContents',
  props: ['contents']
}

</script>
<style scoped>
ul {
  padding-left: 1em;
  border-left: #ccc 1px solid;
  border-top: #ccc 1px solid;
  border-bottom: #ccc 1px solid;
}

</style>
