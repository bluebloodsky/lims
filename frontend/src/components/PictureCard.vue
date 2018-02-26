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
      requied: true
    }
  },
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
      fileList: [],
      realValue: []
    };
  },
  methods: {
    handleRemove(file, fileList) {
      let fileInfo = file.response ? {name:file.response.name,url:file.response.url} : { name: file.name, url: file.url }
      //todo:从后台删除文件
      remove(this.realValue, fileInfo)
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
  mounted() {
    this.value.map(file => {
      this.fileList.push({
        name: file.name,
        // url: file.url
        url: this.cfgInfo.baseURL + 'files/' + file.url
      })
      this.realValue.push({
        name: file.name,
        url: file.url
      })
    })
  }
}

</script>
