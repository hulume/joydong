<?php
namespace App;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

// 患者模型
class Patient extends Authenticatable {
	use Notifiable, HasApiTokens;

	protected $guarded = ['id'];
	protected $hidden = ['password'];

	// 每个患者有多个医疗档案
	public function archives() {
		return $this->hasMany(Archive::class);
	}

	public function setAgeAttribute($birthday) {
		$this->attributes['age'] = Carbon::parse($birthday)->age;
	}

	// 使用手机作为凭据获取accessToken
	public function findForPassport($mobile) {
		return $this->where('mobile', $mobile)->first();
	}

	// 流动人口  App\Patient::floating()->get();
	public function scopeFloating($query) {
		return $query->where('livetype', false);
	}

	// 常住人口 App\Patient::resident()->get();
	// 根据目前需求，数据库里的常驻人口都是老年人，暂时不用使用下面的scope统计
	// 此处分开只为今后扩展考虑
	public function scopeResident($query) {
		return $query->where('livetype', true);
	}

	// 按年龄
	public function scopeAgeBetween($query, $start, $end = null) {
		if (is_null($end)) {
			$end = $start;
		}
		$now = Carbon::now();
		$start = $now->subYears($start)->format('Y-m-d');
		$end = $now->subYears($end)->addYear()->subDay()->format('Y-m-d');
		return $query->where([
			['birthday', '>=', $end],
			['birthday', '<', $start],
		]);
	}
}