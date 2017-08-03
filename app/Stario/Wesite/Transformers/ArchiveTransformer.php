<?php

namespace Star\Wesite\Transformers;

use App\Archive;
use League\Fractal\TransformerAbstract;

class ArchiveTransformer extends TransformerAbstract {
	public function transform(Archive $archive) {
		return [
			'id' => $archive->id,
			'name' => $archive->name,
			'check_unit' => $archive->check_unit,
			'doctor' => $archive->doctor,
			'result' => $archive->result,
		];
	}
}
