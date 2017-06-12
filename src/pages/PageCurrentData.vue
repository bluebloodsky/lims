<template>
  <div>
    <div v-for="item in currentData" class="fixedBox">
      <div class="h">
        <span>{{item.desc_cn}}</span>
      </div>
      <div class="b">
        <template v-if="item.items&&item.items.length>0">
          <div class="desc-item-box">
            <label>采集时间</label>
            <span>{{item.items[0].data_time}}</span>
          </div>
          <div class="desc-item-box" v-for="attr in item.items[0].data_attrs">
            <label>{{attr.name}}</label>
            <template v-if="attr.value">
            <span> {{attr.value}}</span> {{attr.unit}}
            </template>
            <span v-else>---</span>
          </div>
        </template>
        <span v-else>暂无数据</span>
      </div>
    </div>
  </div>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex'

export default {
  computed: {
    ...mapGetters({
      currentData: 'currentData'
    })
  }
}
</script>
<style scoped>
.fixedBox {
  width: 400px;
  height: 400px;
  margin: 2px;
  box-shadow: 0px 0 2px rgba(0, 0, 0, 0.8);
  float: left;
  position: relative;
}

.fixedBox > .h {
  background-color: #def;
}

.fixedBox > .h > span {
  left: 20px;
}
</style>
