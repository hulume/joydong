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

	// public function setPasswordAttribute($password) {
	// 	$this->attributes['password'] = bcrypt($password);
	// }

	public function setAgeAttribute($birthday) {
		$this->attributes['age'] = Carbon::parse($birthday)->age;
	}

	// 使用手机作为凭据获取accessToken
	public function findForPassport($mobile) {
		return $this->where('mobile', $mobile)->first();
	}

	// 流动人口  App\Patient::floating()->get();
	public function scopeFloating($query) {
		return $query->where('livetype', 0);
	}

	// 常住人口 App\Patient::resident()->get();
	public function scopeResident($query) {
		return $query->where('livetype', 1);
	}

	// 65岁以上列入老年人统计
	public function scopeAged($query) {
		return $query->where('age', '>', 64);
	}

}