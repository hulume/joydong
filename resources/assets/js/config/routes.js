import Dashboard from '../views/Dashboard.vue'
import Parent from '../views/Parent.vue'
import Home from '../views/Home.vue'
import Profile from '../views/Profile.vue'
import Event from '../views/Event.vue'
import Task from '../views/Task.vue'
import Unit from '../views/Unit.vue'
import Wesite from '../views/Wesite.vue'
import Aged from '../views/Aged.vue'
let routes = [
    // {
    //     path: '/404',
    //     component: NotFound,
    //     name: '',
    //     hidden: true
    // },
    {
        path: '/',
        component: Dashboard,
        name: '',
        // hidden: true,
        icon: 'dashboard',
        single: true,
        permission: 'general',
        children: [
            { path: '/home', component: Home, name: '控制面板' }
        ]
    },
    {
        path: '/',
        component: Dashboard,
        name: '个人中心',
        icon: 'vcard',
        permission: 'general',
        children: [
            { path: '/home/me/profile', name: '我的资料', component: Profile },
            { path: '/home/me/event', name: '通知提醒', component: Event },
        ]
    },
    // {
    //     path: '/',
    //     component: Dashboard,
    //     name: '',
    //     single: true,
    //     icon: 'flag-o',
    //     permission: 'general',
    //     children: [
    //         { path: '/home/task', component: Task, name: '计划任务'}
    //     ]
    // },
    {
        path: '/',
        component: Dashboard,
        name: '',
        single: true,
        permission: 'manage_units',
        icon: 'users',
        children: [
            { path: '/home/units', component: Unit, name: '部门管理' }
        ]
    },
    {
        path: '/',
        component: Dashboard,
        name: '',
        icon: 'weixin',
        single: true,
        permission: 'manage_wx',
        children: [
            { path: '/home/wechat', component: Wesite, name: '微网站管理' }
        ]
    },
    {
        path: '/',
        component: Dashboard,
        name: '',
        icon: 'blind',
        single: true,
        permission: 'manage_aged',
        children: [
            { path: '/home/aged', component: Aged, name: '老年人口管理' }
        ]
    },
    {
        path: '/',
        component: Dashboard,
        name: '流动人口管理',
        icon: 'street-view',
        permission: 'manage_pops',
        children: [
            { path: '/home/pop/', component: require('../views/pop/index.vue'), name: '流动人口概览' },
            { path: '/home/pop/create', component: require('../views/pop/create.vue'), name: '添加人口管理' },
            { path: '/home/pop/:id/edit', component: require('../views/pop/index.vue'), name: '编辑流动人口' }
        ]
    },
    {
        path: '/',
        component: Dashboard,
        name: '内部人员管理',
        permission: 'manage_users',
        icon: 'user-plus',
        children: [
            { path: '/home/users/', component: require('../views/user/index.vue'), name: '内部人员概览' },
            { path: '/home/user/create', component: require('../views/user/create.vue'), name: '创建内部人员' },
            { path: '/home/user/:id/edit', component: require('../views/user/index.vue'), name: '编辑内部人员' }
        ]
    },
    {
        path: '*',
        redirect: '/home',
        hidden: true
    }

]

export default routes