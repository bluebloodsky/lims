<template>
  <div>
    <DevStatus class="box left-box"></DevStatus>
    <HostStatus class="box right-box"></HostStatus>
  </div>
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
.left-box{
  width: 60%;
  height: 100%;
}
.right-box{
  width: 40%;
  height: 100%;
}
</style>
