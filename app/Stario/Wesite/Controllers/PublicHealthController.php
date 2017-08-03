<?php

namespace Star\Wesite\Controllers;
use App\Archive;
use App\Http\Controllers\Controller;
use App\Patient;
use Illuminate\Http\Request;
use Star\Services\Reporter\PublicHealthReporter;

class PublicHealthController extends Controller {

	public function upload(Request $request) {
		$baseInfo = $this->normalize($request->input('baseInfo'));
		$archive = $this->normalize($request->input('archive'));
		if (empty($request->all())) {
			return response()->json([
				'success' => false,
				'data' => '没有数据需要上传',
			]);
		}
		$archiveData = [
			'checkdate' => $archive['createtime'],
			'doctor' => $archive['doctor'],
			'pub_id' => $archive['pub_id'],
			'village' => $baseInfo['village'],
			'abnormal' => $request->input('abnormal'),
			'result' => [
				'base' =>
				[
					'体温' => $archive['temperature'],
					'左侧血压' => $archive['blood_pressure_left'],
					'右侧血压' => $archive['blood_pressure_right'],
					'体质指数' => $archive['bmi'],
				],
				'blood' => [
					'血红蛋白' => $archive['hgb'],
					'白细胞' => $archive['wbc'],
					'血小板' => $archive['plt'],
				],
				'ecg' => $archive['ecg'],
				'liver' => [
					'血清谷丙转氨酶' => $archive['alt'],
					'血清谷草转氨酶' => $archive['ast'],
					'总胆红素' => $archive['stb'],
				],
				'kidney' => [
					'血清肌酐' => $archive['scr'],
					'血尿素氮' => $archive['bun'],
					'尿酸' => $archive['ua'],
				],
				'fat' => [
					'总胆固醇' => $archive['tcho'],
					'甘油三酯' => $archive['trig'],
					'血清低密度脂蛋白胆固醇' => $archive['ldl'],
					'血清高密度脂蛋白胆固醇' => $archive['hdl'],
				],
				'bray' => $archive['bray'],
				'cn_medicine' => $archive['cn_medicine'],
				'comment' => $archive['comment'],
				'control' => $archive['control'],
			],
		];

		// 修改或者创建患者,公卫中有可能身份证重复，所以使用下面方法
		$patient = Patient::where('identify', $baseInfo['identify'])->first();
		if ($patient === null) {
			$patient = Patient::create($baseInfo);
		} else {
			$patient->update($baseInfo);
		}
		// 提取该患者下面的公卫识别号，看是否存在此记录
		$archive = $patient->archives()->where('pub_id', $archiveData['pub_id'])->first();
		// 如果不存在则创建，如果已存在则修改
		if ($archive === null) {
			$archive = new Archive($archiveData);
			if ($patient->archives()->save($archive)) {
				return response()->json([
					'success' => true,
					'data' => $patient->fn . '成功写入',
				], 200);
			}
			return response()->json([
				'success' => false,
				'data' => $patient->fn . '写入失败',
			], 200);

		} else {
			if ($archive->update($archiveData)) {
				return response()->json([
					'success' => true,
					'data' => $patient->fn . '成功修改',
				], 200);
			}
			return response()->json([
				'success' => false,
				'data' => $patient->fn . '写入失败',
			], 200);
		}
	}

	public function reporter() {
		$month = request()->input('month');
		request()->has('year') ? $year = request()->input('year') : $year = date('Y');
		return PublicHealthReporter::monthly($month);
	}

	/**
	 * 格式化数据
	 */
	private function normalize(array $data) {
		// 如果电话号码空，则伪造一个
		if (array_key_exists('phone', $data)) {
			if (empty($data['phone'])) {
				$data['phone'] = date('ymdHis');
			}
			$data['phone'] = substr($data['phone'], 0, 12);
		}
		// 医生有可能拼写错误，截取
		if (array_key_exists('doctor', $data)) {
			$data['doctor'] = mb_substr($data['doctor'], 0, 10);
		}
		// 女=0 男=1
		if (array_key_exists('gender', $data)) {
			$data['gender'] === '女' ? $data['gender'] = 0 : $data['gender'] = 1;
		}
		// 将评价中多余的字符剔除
		if (array_key_exists('comment', $data)) {
			$data['comment'] = preg_replace('/异常\d:$|异常\d:异常\d:/', '', $data['comment']);
		}
		// 将时间转换成日期
		if (array_key_exists('createtime', $data)) {
			$time = new \DateTime($data['createtime']);
			$data['createtime'] = $time->format('Y-m-d');
		}
		return $data;
	}
}