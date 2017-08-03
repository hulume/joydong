<?php
namespace Star\Wesite\Controllers;

use App\Archive;
use App\Http\Controllers\Controller;

class ArchiveController extends Controller {

	protected $archive;

	public function __construct(Archive $archive) {
		$this->archive = $archive;
	}
	public function show($name) {
		return $this->archive->where('name', $name)->get();
	}
}