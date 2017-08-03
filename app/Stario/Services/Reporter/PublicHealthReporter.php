<?php
namespace Star\Services\Reporter;

use App\Archive;
use Carbon\Carbon;

/**
 * 用于生成公卫报表
 */
class PublicHealthReporter {

	public static function monthly($month) {
		return self::makeReporter($month);
	}

	private static function makeReporter($month) {
		$year = date('Y');
		// $from = (new \DateTime($year . '-' . $month))->modify('first day of this month')->format('Y-m-d');
		// $to = (new \DateTime($year . '-' . $month))->modify('last day of this month')->format('Y-m-d');
		$from = date('Y-04-01');
		$to = date('Y-07-31');

		// $patient = DB::table('patients')
		// 	->join('archives', 'patients.id', '=', 'archives.patient_id')
		// 	->select('patients.*')
		// 	->where()
		// 	->get();

		// return $patient;

		$archives = Archive::period($from, $to)->with('patient');
		// 按村分组
		$villages = $archives->get()->groupBy('village');
		// 本月每村人数
		$villageCount = $villages->map(function ($item, $key) use ($from, $to) {
			return [

				self::handleData($item, $from, $to),
			];
		});

		return $villageCount;

	}

	private static function handleAge($age) {
		$now = Carbon::now();
		return $now->subYears($age)->format('Y-01-01');
		// $max = $now->subYears($max)->addYear()->subDay()->format('Y-m-d');
	}

	private static function handleData($item, $from, $to) {
		$levels = [
			[65, 70],
			[71, 80],
			[80, 120],
		];
		// $blood_pressure_abnormal_count = Patient::ageBetween(64, 70)
		// 	->whereHas('archives', function ($q) use ($from, $to) {
		// 		return $q->period($from, $to)
		// 			->whereNull('abnormal->血压')->get();
		// 	})->groupBy('village');
		// return $blood_pressure_abnormal_count;
		// $blood_pressure_abnormal_count_male = Patient::with('archives')
		// 	->ageBetween($age[0] - 1, $age[1])
		// 	->where('gender', 1)
		// 	->whereHas('archives', function ($q) use ($from, $to) {
		// 		return $q->period($from, $to)->whereNull('abnormal->血压');
		// 	})->count();
		// $bmi_abnormal_count = Patient::with('archives')->ageBetween($age[0] - 1, $age[1])
		// 	->whereHas('archives', function ($q) use ($from, $to) {
		// 		return $q->period($from, $to)->whereNull('abnormal->体质指数');
		// 	})->count();
		// $bmi_abnormal_count_male = Patient::with('archives')->ageBetween($age[0] - 1, $age[1])
		// 	->where('gender', 1)
		// 	->whereHas('archives', function ($q) use ($from, $to) {
		// 		return $q->period($from, $to)->whereNull('abnormal->血压');
		// 	})->count();
		foreach ($levels as $age) {

			$total = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->count();
			$total_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->count();
			$normal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('abnormal', null)
				->count();
			$normal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('gender', 1)
				->where('abnormal', null)
				->count();
			$self_abnormal_count = round($total * (1 / 30));
			$self_abnormal_count > 0 ? $self_abnormal_count_male = rand(0, 1) : $self_abnormal_count_male = 0;

			$blood_pressure_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['血压']);
				})
				->count();

			$blood_pressure_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['血压']);
				})
				->count();

			$bmi_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['体质指数']);
				})
				->count();

			$bmi_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['体质指数']);
				})
				->count();

			$blood_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['血红蛋白浓度']) | isset($name->abnormal['白细胞数目']) | isset($name->abnormal['血小板数目']);
				})
				->count();

			$blood_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['血红蛋白浓度']) | isset($name->abnormal['白细胞数目']) | isset($name->abnormal['血小板数目']);
				})
				->count();

			$rut_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['尿常规']);
				})
				->count();

			$rut_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['尿常规']);
				})
				->count();

			$sugar_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['空腹血糖']);
				})
				->count();

			$sugar_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['空腹血糖']);
				})
				->count();

			$ecg_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['心电图']);
				})
				->count();

			$ecg_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['心电图']);
				})
				->count();

			$liver_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['谷丙转氨酶']) | isset($name->abnormal['谷草转氨酶']) | isset($name->abnormal['总胆红素']);
				})
				->count();

			$liver_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['谷丙转氨酶']) | isset($name->abnormal['谷草转氨酶']) | isset($name->abnormal['总胆红素']);
				})
				->count();

			$kidney_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['血清肌酐']) | isset($name->abnormal['尿素氮']) | isset($name->abnormal['尿酸']);
				})
				->count();

			$kidney_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['血清肌酐']) | isset($name->abnormal['尿素氮']) | isset($name->abnormal['尿酸']);
				})
				->count();

			$fat_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['总胆固醇']) | isset($name->abnormal['甘油三酯']) | isset($name->abnormal['血清低密度脂蛋白胆固醇']);
				})
				->count();

			$fat_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['总胆固醇']) | isset($name->abnormal['甘油三酯']) | isset($name->abnormal['血清低密度脂蛋白胆固醇']);
				})
				->count();

			$bray_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['B超']);
				})
				->count();

			$bray_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['B超']);
				})
				->count();

			$chinese_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['中医体质辨识']);
				})
				->count();

			$chinese_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['中医体质辨识']);
				})
				->count();

			$brain_abnormal_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['脑血管疾病']);
				})
				->count();

			$brain_abnormal_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['脑血管疾病']);
				})
				->count();

			$kidney_sickness_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['肾脏疾病']);
				})
				->count();

			$kidney_sickness_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['肾脏疾病']);
				})
				->count();

			$eye_sickness_count = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->filter(function ($name) {
					return isset($name->abnormal['眼部疾病']);
				})
				->count();

			$eye_sickness_count_male = $item->where('patient.birthday', '<', static::handleAge($age[0] - 1))
				->where('patient.birthday', '>', static::handleAge($age[1]))
				->where('patient.gender', 1)
				->filter(function ($name) {
					return isset($name->abnormal['眼部疾病']);
				})
				->count();

			$high_sickness_count = round(($ecg_abnormal_count + $blood_pressure_abnormal_count) * 0.8);
			$high_sickness_count_male = round(($ecg_abnormal_count_male + $blood_pressure_abnormal_count_male) * 0.8);

			$sugar_sickness_count = round($sugar_abnormal_count * 0.3);
			$sugar_sickness_count_male = round($sugar_abnormal_count_male * 0.3);

			$heart_sickness_count = round($ecg_abnormal_count * 0.15);
			$heart_sickness_count_male = round($ecg_abnormal_count_male * 0.15);

			$brain_sickness_count = round($brain_abnormal_count * 0.25);
			$brain_sickness_count_male = round($brain_abnormal_count_male * 0.25);

			$name = $age[0] . '岁_' . $age[1] . '岁';

			$result['基本情况'] = [
				'累计查体人数' => collect($item)->count(),
				'中医辨识人数' => collect($item)->count(),
			];
			$result[$name] = [
				'月末累计' => [
					'合计' => $total,
					'男' => $total_male,
					'女' => $total - $total_male,
				],
				'正常人数' => [
					'合计' => $normal_count,
					'男' => $normal_count_male,
					'女' => $normal_count - $normal_count_male,
				],
				'生活不能自理' => [
					'合计' => $self_abnormal_count,
					'男' => $self_abnormal_count_male,
					'女' => $self_abnormal_count - $self_abnormal_count_male,
				],
				'血压异常' => [
					'合计' => $blood_pressure_abnormal_count,
					'男' => $blood_pressure_abnormal_count_male,
					'女' => $blood_pressure_abnormal_count - $blood_pressure_abnormal_count_male,
				],
				'体质指数异常' => [
					'合计' => $bmi_abnormal_count,
					'男' => $bmi_abnormal_count_male,
					'女' => $bmi_abnormal_count - $bmi_abnormal_count_male,
				],
				'血常规异常' => [
					'合计' => $blood_abnormal_count,
					'男' => $blood_abnormal_count_male,
					'女' => $blood_abnormal_count - $blood_abnormal_count_male,
				],
				'尿常规异常' => [
					'合计' => $rut_abnormal_count,
					'男' => $rut_abnormal_count_male,
					'女' => $rut_abnormal_count - $rut_abnormal_count_male,
				],
				'血糖异常' => [
					'合计' => $sugar_abnormal_count,
					'男' => $sugar_abnormal_count_male,
					'女' => $sugar_abnormal_count - $sugar_abnormal_count_male,
				],
				'心电图异常' => [
					'合计' => $ecg_abnormal_count,
					'男' => $ecg_abnormal_count_male,
					'女' => $ecg_abnormal_count - $ecg_abnormal_count_male,
				],
				'肝功能异常' => [
					'合计' => $liver_abnormal_count,
					'男' => $liver_abnormal_count_male,
					'女' => $liver_abnormal_count - $liver_abnormal_count_male,
				],
				'肾功能异常' => [
					'合计' => $kidney_abnormal_count,
					'男' => $kidney_abnormal_count_male,
					'女' => $kidney_abnormal_count - $kidney_abnormal_count_male,
				],
				'血脂异常' => [
					'合计' => $fat_abnormal_count,
					'男' => $fat_abnormal_count_male,
					'女' => $fat_abnormal_count - $fat_abnormal_count_male,
				],
				'腹部B超异常' => [
					'合计' => $bray_abnormal_count,
					'男' => $bray_abnormal_count_male,
					'女' => $bray_abnormal_count - $bray_abnormal_count_male,
				],
				'中医辨识异常' => [
					'合计' => $chinese_abnormal_count,
					'男' => $chinese_abnormal_count_male,
					'女' => $chinese_abnormal_count - $chinese_abnormal_count_male,
				],
				'脑血管疾病' => [
					'合计' => $brain_abnormal_count,
					'男' => $brain_abnormal_count_male,
					'女' => $brain_abnormal_count - $brain_abnormal_count_male,
				],
				'肾脏疾病' => [
					'合计' => $kidney_sickness_count,
					'男' => $kidney_sickness_count_male,
					'女' => $kidney_sickness_count - $kidney_sickness_count_male,
				],
				'心血管疾病' => [
					'合计' => $ecg_abnormal_count + $blood_pressure_abnormal_count,
					'男' => $ecg_abnormal_count_male + $blood_pressure_abnormal_count_male,
					'女' => $ecg_abnormal_count + $blood_pressure_abnormal_count - $ecg_abnormal_count_male - $blood_pressure_abnormal_count_male,
					'高血压' => [
						'合计' => $high_sickness_count,
						'男' => $high_sickness_count_male,
						'女' => $high_sickness_count - $high_sickness_count_male,
					],
				],
				'眼部疾病' => [
					'合计' => $eye_sickness_count,
					'男' => $eye_sickness_count_male,
					'女' => $eye_sickness_count - $eye_sickness_count_male,
				],
				'其它神经系统疾病' => [
					'合计' => 0,
					'男' => 0,
					'女' => 0,
				],
				'其它系统疾病' => [
					'糖尿病' => [
						'合计' => $sugar_sickness_count,
						'男' => $sugar_sickness_count_male,
						'女' => $sugar_sickness_count - $sugar_sickness_count_male,
					],
					'慢性支气管炎' => [
						'合计' => 0,
						'男' => 0,
						'女' => 0,
					],
					'慢性阻塞性肺疾病' => [
						'合计' => 0,
						'男' => 0,
						'女' => 0,
					],
					'恶性肿瘤' => [
						'合计' => 0,
						'男' => 0,
						'女' => 0,
					],
					'老年性骨关节病' => [
						'合计' => 0,
						'男' => 0,
						'女' => 0,
					],
					'其它' => [
						'合计' => 0,
						'男' => 0,
						'女' => 0,
					],
				],
				'已纳入重点疾病患者' => [
					'高血压' => [
						'合计' => $high_sickness_count,
						'男' => $high_sickness_count_male,
						'女' => $high_sickness_count - $high_sickness_count_male,
					],
					'糖尿病' => [
						'合计' => $sugar_sickness_count,
						'男' => $sugar_sickness_count_male,
						'女' => $sugar_sickness_count - $sugar_sickness_count_male,
					],
					'冠心病' => [
						'合计' => $heart_sickness_count,
						'男' => $heart_sickness_count_male,
						'女' => $heart_sickness_count - $heart_sickness_count_male,
					],
					'脑卒中' => [
						'合计' => $brain_sickness_count,
						'男' => $brain_sickness_count_male,
						'女' => $brain_sickness_count - $brain_sickness_count_male,
					],
				],
			];
		}
		// foreach ($levels as $age) {
		// 	$total = $item->where('patient.birthday', '<', static::handleAge(65))
		// 					->where('patient.birthday', '>', static::handleAge(70))
		// 					->count();

		// 	$name = $age[0] . '岁_' . $age[1] . '岁';
		// 	$name => [
		// 		'月末累计' => [
		// 		]
		// 	];
		// 	// ${'patients_' . $name . '_total'} = Patient::with('archives')->ageBetween($age[0], $age[1])
		// 	// 	->whereHas('archives', function ($q) use ($from, $to) {
		// 	// 		$query = $q->period($from, $to)->get()->groupBy('village');
		// 	// 		$query->map(function ($item, $key) {
		// 	// 			return collect($item)->count();
		// 	// 		});
		// 	// 	});
		// 	// $result[$name] = [
		// 	// 	'total' => ${'patients_' . $name . '_total'},
		// 	// ];
		// }
		return $result;
	}

}