<template>
  <div>
    <h2>电磁兼容实验室检测委托协议</h2>
    <div class="nav">
      <ul class="left-info">
        <li class="done"><a>基本信息</a></li>
        <li class="current"><a>试品主要技术参数</a></li>
        <li><a>试验项目及主要技术参数</a></li>
      </ul>
      <div class="right-btn">
        <button>流程复制</button>
        <button>下单</button>
        <button>保存</button>
      </div>
    </div>
    <div class="main-wrapper">
      <div class="box top-box">
        <div class="h">
          <span>委托方</span>
          <div class="right-btn">
            <button type="text"><i class="iconfont icon-search"></i></button>
          </div>
        </div>
        <div class="b">
          <form>
            <div class="form-item">
              <span>委托单位</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>联系地址</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>E-mail</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>联系人</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>电话/传真</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>邮编</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="box top-box">
        <div class="h">
          <span>检测方</span>
        </div>
        <div class="b">
          <form>
            <div class="form-item"><span>检测单位</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>联系地址</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>E-mail</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>联系人</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>电话/传真</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>开户单位</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>邮编</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>银行帐户</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>开户银行</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>税号</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="box top-box">
        <div class="h">
          <span>协议内容</span>
        </div>
        <div class="b">
          <form>
            <div class="form-item"><span>检测类别</span>
              <div class="right-info">
                <input type="checkbox" class="checkbox" :checked="ischecked"><span>GB/T 1094.1-2013</span>
                <input type="checkbox" class="checkbox"><span>GB/T 1094.1-2013</span>
                <input type="checkbox" class="checkbox"><span>GB/T 1094.1-2013</span>
              </div>
            </div>
            <div class="form-item"><span>联系地址</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>E-mail</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>联系人</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>电话/传真</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>开户单位</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>邮编</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
            <div class="form-item"><span>银行帐户</span>*
              <div class="right-info">
                <input v-model="client.account" disabled>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      client: {
        account: '国网电力科学研究院武汉南瑞有限责任公司'
      },
      ischecked: false
    }
  }
}

</script>
<style scoped>
.nav {
  margin: 0 15px;
  border: 1px solid #D4D4D4;
  height: 28px;
  line-height: 28px;
}

.top-box {
  position: relative;
  height: 200px;
  width: 100%;
}

.left-info {
  float: left;
}

.left-info>li {
  float: left;
  width: 196px;
  text-align: center;
}

.done {
  background-image: url("../assets/ordernn.png");
  color: white;
}

.current {
  background-image: url("../assets/ordernh.png");
  color: white;
}

.right-btn {
  margin: 0px 3px;
  float: right;
}

.right-btn>button {
  width: 80px;
}

.main-wrapper {
  margin: 10px 15px;
  border: 1px solid #D4D4D4;
}

.form-item {
  width: 45%;
  position: relative;
  margin: 5px 10px;
  text-align: center;
  display: inline-block;
}

.form-item>span {
  width: 150px;
  display: inline-block;
  text-align: left;
}

.right-info {
  width: 350px;
  display: inline-block;
}

.right-info>input {
  width: 100%;
  font-size: 14px;
}

.right-info>.checkbox {
  width: 15px;
  height: 15px;
}

</style>
