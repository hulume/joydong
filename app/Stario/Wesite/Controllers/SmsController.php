<?php
namespace Star\Wesite\Controllers;

use App\Http\Controllers\Controller;
use Star\Wesite\Requests\FireSmsFormRequest;

class SmsController extends Controller {
	public function authCode(FireSmsFormRequest $request) {
		return $request->fire();
	}

}