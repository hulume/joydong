<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model {
	protected $fillable = [
		'result', 'patient_id', 'name',
	];

	protected $casts = [
		'result' => 'array',
	];
	public function pop() {
		return $this->belongsTo(Patient::class);
	}
}
