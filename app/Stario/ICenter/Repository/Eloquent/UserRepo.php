<?php

namespace Star\ICenter\Repository\Eloquent;

use App\User;
use Star\ICenter\Models\Profile;
use Star\Services\FileManager\UploadManager;
use Star\Utils\StarJson;

class UserRepo extends BaseRepository {
	protected $model;
	protected $upload;

	// 定义字段用于前台vuetable检索
	protected $fieldSearchable = [
		'name', 'mobile', 'email',
	];

	/**
	 * 必须在构造器中声明一下使用的模型
	 */
	public function __construct(User $user, UploadManager $upload) {
		$this->model = $user;
		$this->upload = $upload;
	}

	public function changePassword($user, $password) {
		if ($this->update($user->id, ['password' => bcrypt($password)])) {
			// event(new ChangeCredentials($user));
			return StarJson::create('密码成功修改，即将返回登录', 200);
		}
		return StarJson::create('密码修改失败，请联系管理员', 403);
	}

	/**
	 * 将权限直接分配给用户，支持单条和批量，字符传过来需要以逗号分隔
	 * @param  $user 被分配用户的实例
	 * @param  $permissions 权限的label值
	 */
	public function assignPermissions($user, $permissions) {
		if (!is_array($permissions)) {
			$permissions = explode(',', $permissions);
		}
		return $user->givePermissionTo($permissions);
	}

	/**
	 * 支持单条和批量更新用户权限，字符传过来需要以逗号分隔
	 * @param  $user 被分配用户的实例
	 * @param  $permissions 权限的label值
	 */
	public function updatePermissions($user, $permissions) {

		if (!is_array($permissions)) {
			$permissions = explode(',', $permissions);
		}
		return $user->syncPermissions($permissions);
	}

	public function avatar($user, $avatar) {
		$validator = \Validator::make(['file' => $avatar], ['file' => 'image']);
		if ($validator->fails()) {
			return StarJson::create(304);
		}

		$path = 'avatars/' . $user->id;
		$result = $this->upload->store($avatar, $path);
		if (!$result['success']) {
			return StarJson::create(400);
		}
		if (!empty($user->profile->avatar)) {
			$delete = $this->upload->deleteFile($user->profile->avatar);
		}
		$save = $this->saveAvatar($user->id, $result['url']);
		if ($save) {
			return StarJson::create('头像成功更改', 200);
		}
		return StarJson::create('头像修改失败', 403);
	}
	/**
	 * Save the user avatar path.
	 *
	 * @param  int $id
	 * @param  string imgPath$
	 * @return boolean
	 */
	private function saveAvatar($id, $imgPath) {
		$user = $this->with(['profile'])->find($id);
		if (empty($user->profile)) {
			$profile = new Profile(['avatar' => $imgPath]);
			$user->profile()->save($profile);
		}
		return $user->profile->update(['avatar' => $imgPath]);
	}

	/**
	 * 自动判断，新建或更新一个用户对应的个人资料
	 * @param  int $uid   用户id
	 * @param  array $input 相关字段
	 */
	public function saveProfile($uid, $input) {
		$user = $this->model->findOrFail($uid);

		$profileData = $input;
		// if (array_key_exists('sex', $profileData)) {
		//   $profileData['sex'] = $profileData['sex'] == '女' ? 0 : 1;
		// }
		if (empty($user->profile)) {
			$profile = new Profile($profileData);
			return $user->profile()->save($profile);
		}
		return $user->profile()->update($profileData);
	}
}