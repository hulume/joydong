<template>
	<el-row class="container">
		<el-col :span="24" class="header" style="position:fixed">
			<a href="/home" class="logo">
				<el-col v-if="collapsed" class="logo-mini" :span="10">
					<i class="fa fa-podcast"></i>
				</el-col>
				<el-col v-else class="logo-lg" :span="10">
					<i class="fa fa-podcast"></i> <span>{{app}}</span>
				</el-col>
			</a>
			<div class="navbar-wrapper">
				<el-col :span="10">
					<div class="sidebar-toggle" @click.prevent="collapse">
						<i class="fa fa-align-justify"></i>
					</div>
				</el-col>
				<el-col :span="4" class="navbar">
					<div class="navbar-nav">
						<el-dropdown trigger="click" class="user-menu">
							<a class="el-dropdown-link userinfo-inner"><img :src="profile.avatar" /> {{userInfo.name}}</a>
							<el-dropdown-menu slot="dropdown">
								<el-dropdown-item>我的消息</el-dropdown-item>
								<el-dropdown-item>设置</el-dropdown-item>
								<el-dropdown-item divided @click.native="logout">退出登录</el-dropdown-item>
							</el-dropdown-menu>
						</el-dropdown>
					</div>
				</el-col>
			</div>
		</el-col>
	</el-row>
</template>
<script>
	export default {
		data () {
			return {
				collapsed: false,
				app: window.app
			}
		},
		methods: {
			collapse:function(){
				this.collapsed=!this.collapsed;
			}
		},
		computed: {
			userInfo () {
				return this.$store.getters.getUserInfo
			},
			profile () {
				return this.$store.getters.getProfile
			}
		}
	}
</script>
<style scoped lang="scss">
	$color-primary: #3c8dbc;
	.container {
		position: absolute;
		top: 0px;
		bottom: 0px;
		width: 100%;
		.header {
			height: 50px;
			line-height: 50px;
			background: $color-primary;
			color:#fff;
			.navbar-wrapper {
				-webkit-transition: margin-left .3s ease-in-out;
				    -o-transition: margin-left .3s ease-in-out;
				    transition: margin-left .3s ease-in-out;
				    margin-bottom: 0;
				    margin-left: 230px;
				    border: none;
				    min-height: 50px;
				    border-radius: 0;
				.navbar {
					float: right;
					.navbar-nav {
						float: right;
						margin: 0;
						padding-left: 0;
						list-style: none;
						.el-dropdown {
							float: left;
							:hover {
								background: rgba(0,0,0,0.1);
								color: #f6f6f6;
							}
							a {
								color: #fff;
								padding: 15px;
								line-height: 20px;
								position: relative;
								display: block;
								img {
									float: left;
									width: 25px;
									height: 25px;
									border-radius: 50%;
									margin-right: 10px;
									margin-top: -2px;
									max-width: none;
									border: 0;
									vertical-align: middle;
								}
							}
						}
					}
				}
			}
			
			.logo {
				color: #fff;
				-webkit-transition: width .3s ease-in-out;
				    -o-transition: width .3s ease-in-out;
				    transition: width .3s ease-in-out;
				.logo-lg {
					width:230px;
					height:50px;
					font-size: 18px;
					padding-left:20px;
					padding-right:20px;
					text-align: center;
					border-color: rgba(238,241,146,0.3);
					border-right-width: 1px;
					border-right-style: solid;

					// transition: width .3s ease-in-out,-webkit-transform .3s ease-in-out;
					// transition: transform .3s ease-in-out,width .3s ease-in-out;
					// transition: transform .3s ease-in-out,width .3s ease-in-out,-webkit-transform .3s ease-in-out;

					span {
					}
					img {
						width: 40px;
						float: left;
						margin: 10px 10px 10px 18px;
					}
					.txt {
						color:#fff;
					}
				}
				// .logo-lg{
				// 	width:230px;
				// 	transition: width 0.5s;
				// }
				.logo-mini{
					background: rgba(0, 0, 0, 0.1);
					width: 50px;
					text-align: center;
					// display: block;
					// margin-left: -15px;
					// margin-right: -15px;
					font-size: 18px;
				}
			}
			.sidebar-toggle{
				padding: 0px 18px;
				width:14px;
				height: 50px;
				line-height: 50px;
				cursor: pointer;
			}
		}
		.main {
			display: flex;
			// background: #324057;
			position: absolute;
			top: 50px;
			bottom: 0px;
			overflow: hidden;
			aside {
				flex:0 0 230px;
				width: 230px;
				// position: absolute;
				// top: 0px;
				// bottom: 0px;
				.el-menu{
					height: 100%;
				}
				.collapsed{
					width:60px;
					.item{
						position: relative;
					}
					.submenu{
						position:absolute;
						top:0px;
						left:60px;
						z-index:99999;
						height:auto;
						display:none;
					}

				}
			}
			.menu-collapsed{
				flex:0 0 50px;
				width: 60px;
			}
			.menu-expanded{
				flex:0 0 230px;
				width: 230px;
			}
			.content-container {
				// background: #f1f2f7;
				flex:1;
				// position: absolute;
				// right: 0px;
				// top: 0px;
				// bottom: 0px;
				// left: 230px;
				overflow-y: scroll;
				padding: 20px;
				.breadcrumb-container {
					//margin-bottom: 15px;
					.title {
						width: 200px;
						float: left;
						color: #475669;
					}
					.breadcrumb-inner {
						float: right;
					}
				}
				.content-wrapper {
					background-color: #fff;
					box-sizing: border-box;
				}
			}
		}
	}
</style>
