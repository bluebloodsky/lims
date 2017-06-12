<template>
  <div>
    <div class="half-box v-half">
      <div class="half-box h-half">
        <div class="h">
          <i class="mdi iconfont icon-log">
        </i>
          <span>日志文件下载</span>
        </div>
        <div class="b">
          <el-table :data="cfg_filelist.logFiles" border style="width: 100%" v-if="cfg_filelist">
            <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
            </el-table-column>
            <el-table-column fixed="right" label="操作" align="center">
              <template scope="scope">
                <a class="button" :href="cfgInfo.baseURL + scope.row.url" download>
                  <i class="iconfont icon-download"></i>
                </a>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </div>
      <div class="half-box h-half">
        <div class="h">
          <i class="mdi iconfont icon-config-doc">
        </i>
          <span>配置文件下载</span>
        </div>
        <div class="b">
          <el-table :data="cfg_filelist.cfgFiles" border style="width: 100%" v-if="cfg_filelist">
            <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
            </el-table-column>
            <el-table-column fixed="right" label="操作" align="center">
              <template scope="scope">
                <a class="button" :href="cfgInfo.baseURL+scope.row.url" download>
                  <i class="iconfont icon-download"></i>
                </a>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>
    <div class="half-box v-half">
      <div class="h">
        <i class="mdi iconfont icon-file">
        </i>
        <span>ICD上传下载</span>
      </div>
      <div class="b">
        <el-table :data="cfg_filelist.icdFiles" border style="width: 100%" v-if="cfg_filelist">
          <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in fields">
          </el-table-column>
          <el-table-column fixed="right" label="操作" align="center">
            <template scope="scope">
              <a class="button" :href="cfgInfo.baseURL+scope.row.url" download>
                <i class="iconfont icon-download"></i>
              </a>
            </template>
          </el-table-column>
        </el-table>
        <el-upload class="upload-box" name="newfile" drag :action="cfgInfo.baseURL+'icd_upload'" :on-success="uploadSuccess" :on-error="uploadErr">
          <i class="el-icon-upload"></i>
          <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
          <div class="el-upload__tip" slot="tip"><span class="bad">只能上传icd文件</span></div>
        </el-upload>
      </div>
    </div>
  </div>
</template>
<script>
import fileInfo from "../api/fileinfo"
export default {
  data() {
      return {
        fields: [{
          name: 'name',
          caption: '文件名'
        }, {
          name: 'size',
          caption: '大小'
        }, {
          name: 'uptime',
          caption: '更新时间'
        }],
        cfg_filelist: {}
      }
    },
    methods: {
      getAllFile() {
        //利用索引修改时，vue无法触发页面更新
        let tmp = {}
        fileInfo.getCfgFileList(result => {
          console.log(result)
          for (let key in result) {
            let fileItems = result[key]
            tmp[key] = []
            for (var idx in fileItems) {
              let item = fileItems[idx]
              let size = item.size
              let date = new Date(parseInt(item.uptime) * 1000)
              tmp[key].push({
                url: item.url,
                name: item.name,
                size: size < (2 << 10) ? size : size < (2 << 20) ? (size / 1024).toFixed(2) + "K" : (size / 1024 / 1024).toFixed(2) + "M",
                uptime: date.Format("yyyy-MM-dd hh:mm:ss")
              })
            }
          }
          console.log(tmp)
          this.cfg_filelist = tmp
        })
      },
      uploadSuccess() {
        this.getAllFile()
      },
      uploadErr() {
        this.getAllFile()
      }
    },
    mounted() {
      this.getAllFile()
    }
}
</script>
<style scoped>
.half-box {
  border-right: #dfdfdf 1px solid;
  border-bottom: #dfdfdf 1px solid;
  float: left;
  position: relative;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  /* Firefox */
  -webkit-box-sizing: border-box;
  /* Safari */
}

.v-half {
  width: 50%;
  height: 100%;
}

.h-half {
  width: 100%;
  height: 50%;
}

.upload-box {
  margin: 20px
}
</style>
