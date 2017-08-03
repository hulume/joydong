<?php

namespace Star\Wesite\Transformers;

use App\Patient;
use League\Fractal\TransformerAbstract;
use Star\Wesite\Transformers\ArchiveTransformer;

class PatientTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'archives',
	];
	public function transform(Patient $patient) {
		return [
			'id' => $patient->id,
			'name' => $patient->name,
			'identify' => $patient->identify,
			'mobile' => $patient->mobile,
			'age' => $patient->age,
			'birthday' => $patient->birthday,
			'paytype' => $patient->paytype,
			'sex' => $patient->sex,
			'address' => $patient->address,
			'birthplace' => $patient->birthplace,
			'nation' => $patient->nation,
			'memo' => $patient->memo,
		];
	}

	public function includeArchives(Patient $patient) {
		$archives = $patient->archives;
		return $this->collection($archives, new ArchiveTransformer);
	}
}
