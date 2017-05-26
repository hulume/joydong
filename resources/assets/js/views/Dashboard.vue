<template>
	<div  :class="collapsed?'collapsed':''" class="body">
		<div class="wrapper" style="height: auto;">
			<header class="main-header">
				<!-- LOGO -->
				<a href="/login" class="logo">
					<span v-if="collapsed" class="logo-mini"><i class="fa fa-podcast"></i></span>
					<span v-else class="logo-lg"><i class="fa fa-podcast"></i> {{app}}</span>
				</a>
				<nav class="navbar navbar-static-top">
					<a @click.prevent="onCollapse" class="sidebar-toggle"><span class="sr-only">缩小边栏</span></a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li class="dropdown user user-menu">
								<el-dropdown trigger="hover">
									<a class="el-dropdown-link userinfo-inner"><img :src="profile.avatar" class="user-image" /> {{userInfo.name}}</a>
									<el-dropdown-menu slot="dropdown">
										<el-dropdown-item><i class="fa fa-vcard"></i> <router-link to="/home/me/profile">个人资料</router-link></el-dropdown-item>
										<el-dropdown-item><i class="fa fa-comments"></i> <router-link to="/home/me/event">通知提醒</router-link></el-dropdown-item>
										<el-dropdown-item divided><a href="/logout"><i class="fa fa-window-close"></i> 退出登录</a></el-dropdown-item>
									</el-dropdown-menu>
								</el-dropdown>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar" :class="collapsed?'menu-collapsed':'menu-expanded'">
				<section class="sidebar">
				<!-- USER PANEL -->
					<div class="user-panel">
						<div class="pull-left image">
							<img :src="profile.avatar" alt="User Image" class="img-circle">
						</div>
						<div class="pull-left info">
							<p>{{userInfo.name}}</p>
							<a href="#">[ {{profile.nickname}} ]</a>
						</div>
					</div>
					<!-- WEATHER -->
					<p class="weather">{{weather.date}} {{weather.week}} {{weather.weather}} <a class="btn" @click="fetchWeather"><i class="fa fa-refresh"></i></a></p>
					<!--导航菜单-->
					<el-menu :default-active="$route.path" class="sidebar-menu" unique-opened router v-show="!collapsed" theme="dark">
						<template v-for="(item,index) in menu" v-if="!item.hidden">
							<el-submenu :index="index+''" v-if="!item.single">
								<template slot="title"><i :class="'fa fa-' + item.icon"></i> {{item.name}}</template>
								<el-menu-item v-for="child in item.children" :index="child.path" :key="child.path" v-if="!child.hidden">{{child.name}}</el-menu-item>
							</el-submenu>
							<el-menu-item v-if="item.single&&item.children.length>0" :index="item.children[0].path"><i :class="'fa fa-'+item.icon"></i> {{item.children[0].name}}</el-menu-item>
						</template>
					</el-menu>
					<!--导航菜单-折叠后-->
					<ul class="el-menu el-menu--dark sidebar-collapsed" v-show="collapsed" ref="menuCollapsed">
						<li v-for="(item,index) in menu" v-if="!item.hidden" class="el-submenu item">
							<template v-if="!item.single">
								<div class="el-submenu__title" style="padding-left: 20px;" @mouseover="showMenu(index,true)" @mouseout="showMenu(index,false)"><i :class="'fa fa-'+item.icon"></i></div>
								<ul class="el-menu submenu" :class="'submenu-hook-'+index" @mouseover="showMenu(index,true)" @mouseout="showMenu(index,false)"> 
									<li v-for="child in item.children" v-if="!child.hidden" :key="child.path" class="el-menu-item" style="padding-left: 40px;" :class="$route.path==child.path?'is-active':''" @click="$router.push(child.path)">{{child.name}}</li>
								</ul>
							</template>
							<template v-else>
								<li class="el-submenu">
									<div class="el-submenu__title el-menu-item" style="padding-left: 20px;height: 56px;line-height: 56px;padding: 0 20px;" :class="$route.path==item.children[0].path?'is-active':''" @click="$router.push(item.children[0].path)"><i :class="'fa fa-'+item.icon"></i></div>
								</li>
							</template>
						</li>
					</ul>
				</section>
			</aside>
			<div class="content-wrapper">
				<section class="content-header">
					<strong class="title">{{$route.name}}</strong>
					<el-breadcrumb separator="/" class="breadcrumb-inner">
						<el-breadcrumb-item v-for="item in $route.matched" :key="item.path">
							{{ item.name }}
						</el-breadcrumb-item>
					</el-breadcrumb>
				</section>
				<el-col :span="24" class="content" v-loading.body="loading" element-loading-text="数据加载中...">
					<transition name="fade" mode="out-in">
						<router-view></router-view>
					</transition>
				</el-col>
			</div>
		</div>
		
	</div>
</template>
<script>
	export default {
		data () {
			return {
				collapsed: window.localStorage.getItem('wemesh_sidebar') === 'collapsed',
				app: window.app
			}
		},
		methods: {
			onCollapse () {
				this.collapsed = !this.collapsed
				this.collapsed ? window.localStorage.setItem('wemesh_sidebar', 'collapsed') : window.localStorage.setItem('wemesh_sidebar', 'normal')
			},
			fetchWeather () {
				this.$store.dispatch('LOAD_WEATHER')
			},
			showMenu(i,status){
				this.$refs.menuCollapsed.getElementsByClassName('submenu-hook-'+i)[0].style.display=status?'block':'none';
			},
			makeMenu () {
				// window.console.log(this.permissions)
				let routes = this.$router.options.routes
				let permissions = this.$store.getters.getPermissions
				_.forEach(permissions, function(permission) {
				  	_.forEach(routes, function (route) {
				  		if (permission.name === route.permission ) {
				  			this.menu.push(route)
				  		}
				  	})
				})
			}
		},
		computed: {
			weather () {
				return this.$store.getters.weather
			},
			loading () {
				return this.$store.state.loading
			},
			userInfo () {
				return this.$store.getters.userInfo
			},
			profile () {
				return this.userInfo.profile
			},
			menu () {
				let routes = this.$router.options.routes
				let menu = []
				let permissions = this.$store.getters.permissions
				_.forEach(permissions, function(permission) {
				  	_.forEach(routes, function (route) {
				  		if (permission.name === route.permission ) {
				  			menu.push(route)
				  		}
				  	})
				})
				return menu
			}
		},
		mounted () {
			let localStorage = window.localStorage.getItem('wemesh_sidebar')
			if (localStorage === null) {
				window.localStorage.setItem('wemesh_sidebar', 'normal')
			}
			this.$store.dispatch('LOAD_USER_INFO')
			this.fetchWeather()
		}
	}
</script>
