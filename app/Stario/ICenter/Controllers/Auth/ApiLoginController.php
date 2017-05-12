<?php
namespace Star\ICenter\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Star\ICenter\Proxy\PatientProxy;
use Star\ICenter\Requests\LoginRequest;

class ApiController extends Controller {
	private $loginProxy;
	public function __construct(PatientProxy $loginProxy) {
		$this->loginProxy = $loginProxy;
	}

	public function login(LoginRequest $request) {
		$data = [
			'mobile' => $request->get('mobile'),
			'password' => $request->get('password'),
			// 'provider' => $request->get('provider'),
		];
		return $this->loginProxy->attemptLogin($data);
	}

	public function refresh(Request $request) {
		return $this->response($this->loginProxy->attemptRefresh());
	}

	public function logout() {
		$this->loginProxy->logout();
		return $this->response(null, 204);
	}
}
