<template>
  <div class="login-box">
    <h1>{{title}}</h1>
    <el-form style="width:250px;">
      <el-form-item>
        <el-input type="text" id="username" placeholder="用户名" v-model="username">
          <template slot="prepend"><i class="iconfont icon-account"></i></template>
        </el-input>
      </el-form-item>
      <el-form-item>
        <el-input type="password" id="password" placeholder="密码" v-model="password">
          <template slot="prepend"><i class="iconfont icon-password"></i></template>
        </el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="login" class="primary" @keyup.enter="login">登录</el-button>
        <el-button @click="$router.push('/register')">注册</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import auth from '../api/auth'
export default {
  props: {
    token: {
      type: String
    },
    title: {
      type: String
    }
  },
  data() {
    return {
      username: '',
      password: '',
      login_false: false
    }
  },
  methods: {
    login() {
      let user = {
        username: this.username,
        password: this.password
      }
      auth.login(user, (token) => {
        this.$router.push('/status')
      }, () => {
        this.$message({
          message: '登录失败',
          type: 'warning'
        });
      })
    }

  }
}
</script>
<style scoped>
.login-box {
  width: 600px;
  padding: 50px;
  margin: 40px auto;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  z-index: 100001;
}

h1 {
  color: #678eb0;
}

.iconfont {
  font-size: 20px;
  color: #678eb0;
}
</style>
