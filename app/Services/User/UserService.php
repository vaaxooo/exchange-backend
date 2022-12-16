<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Verification;

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
				'code' => 400,
				'status' => 'error',
				'errors' => $validator->errors()
			];
		}
		if ($request->oldPassword == $request->newPassword) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Old password and new password cannot be the same'
			];
		}
		$user = User::find($request->user()->id);
		if (!Hash::check($request->oldPassword, $user->password)) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Old password is incorrect'
			];
		}

		$user->password = Hash::make($request->newPassword);
		$user->save();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Password changed successfully'
		];
	}

	/**
	 * It takes two images, uploads them to a cloud storage, and saves the URLs to the database
	 * 
	 * @param request The request object.
	 * 
	 * @return An array with the following keys:
	 */
	public function sendVerification($request)
	{
		$validator = Validator::make($request->all(), [
			'inside_passport' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'outside_passport' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'errors' => $validator->errors()
			];
		}
		$insideURL = $this->loadImage($request->file('inside_passport'));
		$outsideURL = $this->loadImage($request->file('outside_passport'));

		$verification = Verification::where('user_id', $request->user()->id)->first();
		if ($verification) {
			$verification->inside_passport = $insideURL;
			$verification->outside_passport = $outsideURL;
			$verification->save();
		} else {
			Verification::create([
				'user_id' => $request->user()->id,
				'inside_passport' => $insideURL,
				'outside_passport' => $outsideURL
			]);
		}
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Verification request submitted'
		];
	}


	/**
	 * It takes an image, gets its name and extension, renames it with a timestamp, and moves it to the
	 * public/images folder
	 * 
	 * @param image The image file that was uploaded.
	 * 
	 * @return The image name.
	 */
	private function loadImage($image)
	{
		$filename = $image->getClientOriginalName();
		$extension = $image->getClientOriginalExtension();
		$picture = date('His') . '-' . $filename;
		$destinationPath = public_path('/images');
		$image->move($destinationPath, $picture);
		return "/" . $picture;
	}
}
