<template>
  <section>
    <el-upload :action="cfgInfo.baseURL + 'files'" name="newfile" list-type="picture-card" :on-success="uploadSuccess" :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :file-list="fileList" with-credentials>
      <i class="el-icon-plus"></i>
    </el-upload>
    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="dialogImageUrl" alt="">
    </el-dialog>
  </section>
</template>
<script>
import { remove } from '@/shared/util'
export default {
  props: {
    value: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false
    };
  },
  methods: {
    handleRemove(file, fileList) {
      //todo:从后台删除文件
      this.axios.delete(file.url)
      this.realValue.splice(file.index, 1)
      this.$emit('input', this.realValue)
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    uploadSuccess(response, file, fileList) {
      this.realValue.push({
        name: response.name,
        url: response.url
      })
      this.$emit('input', this.realValue)
    }
  },
  computed: {
    realValue() {
      return this.value.map(file => {
        return { name: file.name, url: file.url }
      })
    },
    fileList() {
      return this.realValue.map((file, index) => {
        return {
          index: index,
          name: file.name,
          // url: file.url
          url: this.cfgInfo.baseURL + 'files/' + file.url
        }
      })
    }
  },
  mounted() {

  }
}
</script>
<style scoped>
.el-upload-list--picture-card > .el-upload-list__item,
.el-upload--picture-card {
  width: 80px;
  height: 80px;
  line-height: 80px;
}

</style>
