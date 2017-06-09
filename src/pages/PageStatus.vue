<template>
  <el-row>
    <el-col :span="16" style="position:relative;height:100%">
      <DevStatus></DevStatus>
    </el-col>
    <el-col :span="8" style="position:relative;height:100%">
      <HostStatus></HostStatus>
    </el-col>
  </el-row>
</template>
<script>
import DevStatus from '@/pages/status/DevStatus'
import HostStatus from '@/pages/status/HostStatus'
export default {
  components: {
    DevStatus,
    HostStatus
  }
}
</script>
<style scoped>

</style>
