<template>
  <div class="navbar">
    <hamburger id="hamburger-container" :is-active="sidebar.opened" class="hamburger-container" @toggleClick="toggleSideBar" />
    <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />

    <div class="right-menu">
      <el-dropdown @command="handleDropdownCommand">
        <span>
          <el-avatar class="avatar-container" size="medium" :src="avatar+'?imageView2/1/w/80/h/80'" />
          <el-link class="avatar-text" :underline="false">{{ user.phone }}</el-link>
        </span>
        <el-dropdown-menu slot="dropdown">
          <router-link to="#">
            <el-dropdown-item icon="el-icon-user">个人中心</el-dropdown-item>
          </router-link>
          <router-link to="#">
            <el-dropdown-item icon="el-icon-setting">个人设置</el-dropdown-item>
          </router-link>
          <el-dropdown-item command="LOGOUT" divided icon="el-icon-d-arrow-right">退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Breadcrumb from '@/components/Breadcrumb'
import Hamburger from '@/components/Hamburger'

export default {
  components: {
    Breadcrumb,
    Hamburger
  },
  computed: {
    avatar: function() {
      return ''
    },
    ...mapGetters([
      'user',
      'sidebar',
      'device'
    ])
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar')
    },
    handleDropdownCommand(command) {
      if (command === 'LOGOUT') {
        this.logout()
      }
    },
    async logout() {
      await this.$store.dispatch('auth/logout')
      this.$router.push(`/login`)
    }
  }
}
</script>

<style lang="scss" scoped>
.navbar {
  height: 50px;
  padding: 0 16px;
  overflow: hidden;
  position: relative;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0,21,41,.08);

  .hamburger-container {
    line-height: 46px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: background .3s;
    -webkit-tap-highlight-color:transparent;

    &:hover {
      background: rgba(0, 0, 0, .025)
    }
  }

  .breadcrumb-container {
    float: left;
  }

  .right-menu {
    float: right;
    // height: 100%;
    margin: 7px 8px 7px 0;

    .avatar-container {
      display: inline-block;
      vertical-align: middle;
    }
  }
}
</style>
