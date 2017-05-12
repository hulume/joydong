<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model {
	protected $fillable = [
		'result', 'patient_id',
	];

	protected $casts = [
		'result' => 'array', 'data' => 'array', 'innormal' => 'array',
	];
	public function pop() {
		return $this->belongsTo(Patient::class);
	}
}
