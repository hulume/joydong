	export const API_ROOT = (process.env.NODE_ENV === 'production')
		? 'http://jd.wemesh.cn/api/'
		: 'http://jiaodongHospital.dev/api/'

	export const COLOR_BG = [
		'aqua', 'yellow', 'maroon', 'orange', 'purple', 'olive'
	]
	export const COLOR_BS = [
		'danger', 'success', 'info', 'warning', 'primary'
	]

	export const COLOR_SB = [
		'primary', 'warning', 'info', 'success', 'danger'
	]

	export const ICON_MAP = {
		'user': 'user',
		'post': 'edit'
	}

	export const ICONS = [
		"user-md", "hand-o-up", "child", "medkit", "hospital-o", "paste", "id-card-o", "user-plus", "ambulance", "plus-square", "wheelchair-alt", "h-square", "heartbeat", "heart-o", "thermometer", "envelope-o", "flask", "home", "male", "female", "users", "phone-square"
	]

	export const PAY_TYPE = [
		'城镇职工基本医疗保险', '城镇居民基本医疗保险', '新型农村合作医疗', '贫困救助', '商业医疗保险', '全公费', '全自费'
	]

	export const COUNTRY = [
		{
			name: '大麻湾一',
			py: 'dmwy'
		},
		{
			name: '大麻湾二',
			py: 'dmwe'
		},
		{
			name: '大麻湾三',
			py: 'dmws'
		},
		{
			name: '大半窑',
			py: 'dby'
		},
		{
			name: '东小屯',
			py: 'dxt'
		},
		{
			name: '葛家庄',
			py: 'gjz'
		},
		{
			name: '三官庙',
			py: 'sgm'
		},
		{
			name: '周家庄',
			py: 'zjz'
		},
		{
			name: '荒庄',
			py: 'hz'
		},
		{
			name: '小麻湾东村',
			py: 'xmwdc'
		},
		{
			name: '小麻湾西村',
			py: 'xmwxc'
		},
		{
			name: '腊行',
			py: 'lh'
		},
		{
			name: '葛埠岭',
			py: 'gbl'
		},
		{
			name: '三角湾',
			py: 'sjw'
		},
		{
			name: '南庄一',
			py: 'nzy'
		},
		{
			name: '南庄二',
			py: 'nze'
		},
		{
			name: '南庄三',
			py: 'nzs'
		},
		{
			name: '南庄四',
			py: 'nzs'
		},
		{
			name: '河西屯',
			py: 'hxt'
		},
		{
			name: '小西庄',
			py: 'xxz'
		},
		{
			name: '大姜戈庄',
			py: 'djgz'
		},
		{
			name: '和平庄',
			py: 'hpz'
		},
		{
			name: '朱家庄',
			py: 'zjz'
		},
		{
			name: '宋家泊子',
			py: 'sjpz'
		},
		{
			name: '小姜戈庄',
			py: 'xjgz'
		},
		{
			name: '高家庄',
			py: 'gjz'
		},
		{
			name: '十里铺',
			py: 'slp'
		},
		{
			name: '韩信沟',
			py: 'hxg'
		},
		{
			name: '朱家屯',
			py: 'zjt'
		},
		{
			name: '石家庄',
			py: 'sjz'
		},
		{
			name: '温家庄',
			py: 'wjz'
		},
		{
			name: '胶东',
			py: 'jd'
		},
		{
			name: '前辛庄',
			py: 'qxz'
		},
		{
			name: '后辛庄',
			py: 'hxz'
		},
		{
			name: '安家村',
			py: 'ajc'
		},
		{
			name: '贾庄',
			py: 'jz'
		},
		{
			name: '斜沟崖',
			py: 'xgy'
		},
		{
			name: '前丰隆屯',
			py: 'qflt'
		},
		{
			name: '后丰隆屯',
			py: 'hflt'
		},
		{
			name: '罗家村',
			py: 'ljc'
		},
		{
			name: '于家村',
			py: 'yjc'
		},
		{
			name: '二铺',
			py: 'ep'
		},
		{
			name: '周王庄',
			py: 'zwz'
		},
		{
			name: '前店口',
			py: 'qdk'
		},
		{
			name: '后店口',
			py: 'hdk'
		},
		{
			name: '谈家庄',
			py: 'tjz'
		},
		{
			name: '刘家店',
			py: 'ljd'
		},
		{
			name: '圈子',
			py: 'jz'
		},
		{
			name: '大店',
			py: 'dd'
		},
		{
			name: '河西店',
			py: 'hxd'
		},
		{
			name: '南堤子',
			py: 'ndz'
		},
		{
			name: '北堤子',
			py: 'bdz'
		},
		{
			name: '汪家庄',
			py: 'wjz'
		},
		{
			name: '小铺',
			py: 'xp'
		},
		{
			name: '张王庄',
			py: 'zwz'
		},
		{
			name: '爱国庄',
			py: 'agz'
		},
		{
			name: '八里庄',
			py: 'blz'
		},
		{
			name: '白家屯',
			py: 'bjz'
		},
		{
			name: '东草泊',
			py: 'dcp'
		},
		{
			name: '西草泊',
			py: 'xcp'
		},
		{
			name: '杨家屯',
			py: 'yjt'
		},
		{
			name: '葛戈庄',
			py: 'ggz'
		},
		{
			name: '韩家一',
			py: 'hjy'
		},
		{
			name: '韩家二',
			py: 'hje'
		},
		{
			name: '韩家三',
			py: 'hjs'
		},
		{
			name: '大寨',
			py: 'dz'
		},
		{
			name: '官庄',
			py: 'gz'
		},
		{
			name: '小寨',
			py: 'xz'
		},
		{
			name: '大铺',
			py: 'dp'
		},
		{
			name: '朱家',
			py: 'zj'
		}
	]
