<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{

	/**
	 * It validates the request, checks if the old password is correct, and if it is, it changes the
	 * password
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a status and message.
	 */
	public function changePassword($request)
	{
		$validator = Validator::make($request->all(), [
			'oldPassword' => 'required',
			'newPassword' => 'required|min:6',
			'confirmPassword' => 'required|same:newPassword'
		]);
		if ($validator->fails()) {
			return [
				'status' => 'error',
				'message' => $validator->errors()
			];
		}
		if ($request->oldPassword == $request->newPassword) {
			return [
				'status' => 'error',
				'message' => 'Old password and new password cannot be the same'
			];
		}
		if ($request->newPassword != $request->confirmPassword) {
			return [
				'status' => 'error',
				'message' => 'New password and confirm password do not match'
			];
		}
		$user = User::find($request->user()->id);
		if (!Hash::check($request->oldPassword, $user->password)) {
			return [
				'status' => 'error',
				'message' => 'Old password is incorrect'
			];
		}

		$user->password = Hash::make($request->newPassword);
		$user->save();
		return [
			'status' => 'success',
			'message' => 'Password changed successfully'
		];
	}
}
