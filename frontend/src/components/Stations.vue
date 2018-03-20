<template>
  <ul>
    <li v-for="(station,index) in stations">
      <a @click="chooseStation(index)" :class="{disabled:(choosed.length > 0&&index<choosed[0])||(choosed.length == 2 &&index>choosed[1])}">{{station}}</a>
    </li>
  </ul>
</template>
<script>
export default {
  data() {
    return {
      stations: ['武汉', '咸宁北', '岳阳东', '长沙南', '株洲西', '衡阳东', '耒阳西'],
      choosed: []
    }
  },
  methods: {
    chooseStation(index) {
      if (this.choosed.length < 2 && this.choosed.indexOf(index) == -1) {
        this.choosed.push(index)
      }
    }
  }
}

</script>
<style scoped>
ul {
  list-style-type: none;
  width: 90%;
  margin: 0 auto;
  overflow: hidden;
  margin-top: 80px;
}

li {
  position: relative;
  width: 40%;
  height: 40px;
  line-height: 40px;
  padding-bottom: 50px;
  float: left;
}

li a {
  float: inherit;
  background-color: blue;
  width: 50%;
  height: 100%;
  border-radius: 5px;
  color: #fff;
  text-align: center;
}

li:nth-child(3n) {
  width: 20%;
}

li:nth-child(3n) a {
  width: 100%;
}

li:nth-child(3n + 1):before,
li:nth-child(3n + 2):before {
  content: '';
  position: absolute;
  left: 50%;
  right: 0;
  background-color: blue;
  height: 2px;
  margin-top: 20px;
  transform: translateY(-50%);
}

li:nth-child(6n - 1):before,
li:nth-child(6n - 2):before {
  content: '';
  position: absolute;
  left: 0;
  right: 50%;
  background-color: blue;
  height: 2px;
  margin-top: 20px;
  transform: translateY(-50%);
}

li:nth-child(3n):before {
  content: '';
  position: absolute;
  left: 0;
  top: 40px;
  bottom: 0;
  background-color: blue;
  width: 2px;
  margin-left: 50%;
  transform: translateX(-50%);
}

li:nth-child(6n),
li:nth-child(6n-1),
li:nth-child(6n-2) {
  float: right;
}

li:last-child:before {
  background-color: transparent;
}

.disabled {
  background: gray;
  pointer-events: none;
  cursor: default;
  opacity: 0.6;
}

</style>
