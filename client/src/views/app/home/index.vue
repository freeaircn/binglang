<template>
  <div class="home-container">
    <div class="jumbotron-wrapper">
      <div class="jumbotron-container container-xl px-responsive">
        <el-row :gutter="10" class="v-center sm-and-down-hidden">
          <el-col :xs="0" :sm="8" :md="8" :lg="8" :xl="8" class="v-center-item">
            <h1 class="jumbotron-head is-center">Built for us</h1>
            <p class="jumbotron-subhead is-center">融合，共享，触手可及的信息平台</p>
          </el-col>
          <el-col :xs="0" :sm="16" :md="16" :lg="16" :xl="16" class="v-center-item">
            <div>
              <img class="is-center img-responsive" src="@/assets/app/home_lg.svg" alt="web_pic">
            </div>
          </el-col>
        </el-row>
        <div>
          <img class="is-center img-responsive sm-and-up-hidden" src="@/assets/app/home_xs.svg" alt="web_pic">
        </div>
      </div>
    </div>
    <div class="content-wrapper py-responsive">
      <div class="content-container container-xl px-responsive">
        <el-divider><h2 class="content-head is-center">从这里开始</h2></el-divider>
        <el-row :gutter="30">
          <el-col :xs="24" :sm="8" :md="8" :lg="8" :xl="8">
            <el-card shadow="hover" class="is-center mb-2">
              <img class="w-20" src="@/assets/app/start_stop.svg" alt="web_pic">
              <h4 class="content-subhead">机组启停</h4>
              <el-divider />
              <router-link to="/generator/start_stop/log">查看详情</router-link>
            </el-card>
          </el-col>
          <el-col :xs="24" :sm="8" :md="8" :lg="8" :xl="8">
            <el-card shadow="hover" class="is-center mb-2">
              <img class="w-20" src="@/assets/app/electricity_quantity.svg" alt="web_pic">
              <h4 class="content-subhead">电量</h4>
              <el-divider />
              <router-link to="#">查看详情</router-link>
            </el-card>
          </el-col>
          <el-col :xs="24" :sm="8" :md="8" :lg="8" :xl="8">
            <el-card shadow="hover" class="is-center mb-2">
              <img class="w-20" src="@/assets/app/others.svg" alt="web_pic">
              <h4 class="content-subhead">更多功能</h4>
              <el-divider />
              <router-link to="#">查看详情</router-link>
            </el-card>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>

<script>
// import { Constants } from '@/Constants'
// import store from '@/store'
// import { getToken } from '@/utils/auth' // 验权

export default {
  name: 'Home'
}
</script>

<style rel="stylesheet/scss" lang="scss" scope>
// 引入全局样式 https://segmentfault.com/a/1190000015228321

.home-container {
  // position: relative !important;
  // top: 100px; left: 0;
  width: 100%;
}
.jumbotron-wrapper {
  width: 100%;
  background: $jumbotron-bg;
}
.v-center {
  display: flex;
  justify-content:center;
  align-items:Center;
}
.jumbotron-head {
  color: $title-color;
  font-weight: 400;
}
.jumbotron-subhead {
  color: $text-color;
  letter-spacing: 0.05em;
  opacity: 0.8;
}

.content-wrapper {
  width: 100%;
  background: white;
}
.content-container {
  p {
    color: $text-color;
  }
}

.el-card__body {
  a {
    font-size: 14px;
    color: #409eff;
    text-align: center;
    cursor: pointer;
    width: 100%;
    display: block;
  }
}
.content-head {
  color: $title-color;
  font-weight: 400;
}
.content-subhead {
  color: $title-color;
  font-weight: 400;
}
</style>
