<template>
	<!-- 信息卡片开始 -->
	<div class="box box-primary">
		<div class="box-body box-profile">
			<img :src=profile.avatar alt="" class="profile-user-img img-responsive img-circle">
			<h3 class="profile-username text-center">{{data.name}}</h3>
			<p class="text-muted text-center">{{data.unit}}</p>
			<ul class="list-group list-group-unbordered">
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 我的生日
					<span class="pull-right text-blue">{{data.diffMyDays}}</span>
				</li>
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 注册时间
					<span class="pull-right text-blue">{{data.created_at}}</span>
				</li>
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 上次登录
					<span class="pull-right text-blue">{{data.last_login}}</span>
				</li>
				<li class="list-group-item">
					<i class="text-muted fa fa-id-badge"></i> 上次登录IP
					<span class="pull-right text-blue">{{data.last_ip}}</span>
				</li>
			</ul>
			<strong><i class="fa fa-user-o margin-r-5"></i> 我的角色</strong>
			<label-group :lists="data.rolemission.roles"></label-group>
			<strong><i class="fa fa-key margin-r-5"></i> 我的权限</strong>
			<label-group :lists="data.rolemission.permissions"></label-group>
		</div>
	</div>
	<!-- 信息卡片END -->
</template>
<script>
	import LabelGroup from './LabelGroup'
	export default {
		props: {
			data: Object
		},
		computed: {
			profile () {
				return this.$store.getters.getUserInfo.profile
			}
		},
		components: {
			LabelGroup
		}
	}
</script>
<style>
	.profile-user-img {
	  margin: 0 auto;
	  width: 100px;
	  padding: 3px;
	  border: 3px solid #d2d6de;
	}
	.profile-username {
	  font-size: 21px;
	  margin-top: 5px;
	}
	.post {
	  border-bottom: 1px solid #d2d6de;
	  margin-bottom: 15px;
	  padding-bottom: 15px;
	  color: #666;
	}
	.post:last-of-type {
	  border-bottom: 0;
	  margin-bottom: 0;
	  padding-bottom: 0;
	}
	.post .user-block {
	  margin-bottom: 15px;
	}
</style>