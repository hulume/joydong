<?php
namespace Star\Wesite\Controllers;

use Star\ICenter\Controllers\Api\ApiController;
use Star\Wesite\Repository\Eloquent\PatientRepo;
use Star\Wesite\Transformers\PatientTransformer;

/**
 * 用于内部管理使用的Patient控制器
 * 患者类型有aged/pregant/baby/float等
 * 不指定scope HTTP参数则默认为常住人口(resident)，还可以传递include=archives(复数形式)带上archives数据
 * http://jiaodonghospital.dev/api/patient?scope=floating&include=archives
 */
class PatientController extends ApiController {

	protected $patient;
	protected $scoped;

	public function __construct(PatientRepo $patient) {
		parent::__construct();
		isset($_GET['scope']) ? $this->scoped = $_GET['scope'] : $this->scoped = 'resident';
		$this->patient = $patient;
	}
	public function index() {
		$request = request();
		$start_date = $request['start_date'];
		$end_date = $request['end_date'];
		if ($start_date == null) {
			// 默认，只显示最近的50条
			$patients = $this->patient->withScopeTableProvider($this->scoped);
			return $this->respondWithPaginator($patients, new PatientTransformer());
		}
		$patients = $this->patient->withScopeTableProvider($this->scoped);
		return $this->respondWithPaginator($patients, new PatientTransformer());
	}
	public function edit($id) {
		return $this->respondWithItem($this->patient->find($id), new PatientTransformer);
	}
}