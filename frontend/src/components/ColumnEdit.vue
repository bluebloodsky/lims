<template>
  <div class="box">
    <div class="h">
      <span>编辑列</span>
    </div>
    <div class="b">
      <!--列属性列表-->
      <div class="box">
        <div class="h">
          <span>选定的列</span>
        </div>
        <div class="b">
          <el-tree :data="testItemAttrs" highlight-current :props="treeProps" @node-click="changeItem">
          </el-tree>
          <el-button @click="">提交</el-button>
          <el-button @click="">删除</el-button>
        </div>
      </div>
      <!--列上下移动-->
      <div>
        <el-button @click="">提交</el-button>
        <el-button @click="">删除</el-button>
      </div>
      <!--列具体属性-->
      <div class="box">
        <div class="h">
          <span>属性</span>
        </div>
        <div class="b">
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default{
	
}
</script>
<style>


</style>
