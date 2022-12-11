<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoveryPassword;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthService
{

	/**
	 * It checks if the user is authenticated, and if not, it returns a JSON response with a 401 status
	 * code
	 */
	public function login()
	{
		$credentials = request(['email', 'password']);
		if (!$token = auth()->attempt($credentials)) {
			return response()->json([
				'code' => 401,
				'message' => 'E-mail or password is wrong',
				'status' => 'error'
			]);
		}
		return $this->respondWithToken($token);
	}

	/**
	 * It's a function that registers a user.
	 */
	public function register()
	{
		$validator = Validator::make(request()->all(), [
			'email' => 'required|string|email|unique:users,email',
			'password' => 'required|string',
		]);
		if ($validator->fails()) {
			return response()->json([
				'code' => 400,
				'message' => $validator->errors(),
				'status' => 'error'
			]);
		}

		$code = md5(date('Y-m-d H:i:s') . ':' . rand(100000, 999999));
		$user = User::create([
			'email' => request('email'),
			'password' => bcrypt(request('password')),
			'verification_code' => $code
		]);

		Mail::to(request('email'))->send(new VerifyEmail($code));

		$token = auth()->login($user);
		return $this->respondWithToken($token);
	}

	/**
	 * It takes the email from the request, checks if the user exists, generates a random code, saves it to
	 * the user's record, and sends an email with the code
	 * 
	 * @param Request request The request object.
	 * 
	 * @return return response()->json([
	 * 			'code' => 200,
	 * 			'message' => 'Recovery code has been sent to your email',
	 * 			'status' => 'success'
	 * 		]);
	 */
	public function sendMail()
	{
		$validator = Validator::make(request()->all(), [
			'email' => 'required|string|email',
		]);
		if ($validator->fails()) {
			return response()->json([
				'code' => 400,
				'message' => $validator->errors(),
				'status' => 'error'
			]);
		}
		$user = User::where('email', request('email'))->first();
		if (!$user) {
			return response()->json([
				'code' => 400,
				'message' => 'User not found',
				'status' => 'error'
			]);
		}
		$code = md5(date('Y-m-d H:i:s') . ':' . rand(100000, 999999));
		$user->recovery_code = $code;
		$user->save();
		Mail::to(request('email'))->send(new RecoveryPassword(request('email'), $code));
		return response()->json([
			'code' => 200,
			'message' => 'Recovery code has been sent to your email',
			'status' => 'success'
		]);
	}

	/**
	 * It checks if the user exists, if the code is correct, and if it is, it removes the code from the
	 * database
	 * 
	 * @param request The request object.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function verifyCode()
	{
		$validator = Validator::make(request()->all(), [
			'email' => 'required|string|email',
			'code' => 'required|string',
		]);
		if ($validator->fails()) {
			return response()->json([
				'code' => 400,
				'message' => $validator->errors(),
				'status' => 'error'
			]);
		}
		$user = User::where('email', request('email'))->first();
		if (!$user) {
			return response()->json([
				'code' => 400,
				'message' => 'User not found',
				'status' => 'error'
			]);
		}
		if ($user->recovery_code != request('code')) {
			return response()->json([
				'code' => 400,
				'message' => 'Code is wrong',
				'status' => 'error'
			]);
		}
		return response()->json([
			'code' => 200,
			'message' => 'Code is correct',
			'status' => 'success'
		]);
	}

	/**
	 * It takes the email, code and password from the request, checks if the user exists, if the code is
	 * correct and if it is, it changes the password and returns a success message
	 */
	public function resetPassword()
	{
		$validator = Validator::make(request()->all(), [
			'email' => 'required|string|email',
			'code' => 'required|string',
			'password' => 'required|string',
		]);
		if ($validator->fails()) {
			return response()->json([
				'code' => 400,
				'message' => $validator->errors(),
				'status' => 'error'
			]);
		}
		$user = User::where('email', request('email'))->first();
		if (!$user) {
			return response()->json([
				'code' => 400,
				'message' => 'User not found',
				'status' => 'error'
			]);
		}
		if ($user->recovery_code != request('code')) {
			return response()->json([
				'code' => 400,
				'message' => 'Code is wrong',
				'status' => 'error'
			]);
		}
		$user->password = bcrypt(request('password'));
		$user->recovery_code = null;
		$user->save();
		return response()->json([
			'code' => 200,
			'message' => 'Password has been changed',
			'status' => 'success'
		]);
	}

	/**
	 * It generates a random code, saves it to the database, and sends it to the user's email
	 */
	public function verifyCodeEmail()
	{
		$user = User::where('email', auth()->user()->email)->first();
		if (!$user) {
			return response()->json([
				'code' => 400,
				'message' => 'User not found',
				'status' => 'error'
			]);
		}

		$code = md5(date('Y-m-d H:i:s') . ':' . rand(100000, 999999));
		$user->verification_code = $code;
		$user->save();

		Mail::to(auth()->user()->email)->send(new VerifyEmail($code));

		return response()->json([
			'code' => 200,
			'message' => 'Verification code has been sent to your email',
			'status' => 'success'
		]);
	}

	/**
	 * It checks if the code is correct, if it is, it sets the verification code to null and returns a
	 * success message
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function verifyEmail()
	{
		$validator = Validator::make(request()->all(), [
			'code' => 'required|string',
		]);
		if ($validator->fails()) {
			return response()->json([
				'code' => 400,
				'message' => $validator->errors(),
				'status' => 'error'
			]);
		}
		$user = User::where('email', auth()->user()->email)->first();
		if (!$user) {
			return response()->json([
				'code' => 400,
				'message' => 'User not found',
				'status' => 'error'
			]);
		}
		if ($user->verification_code != request('code')) {
			return response()->json([
				'code' => 400,
				'message' => 'Code is wrong',
				'status' => 'error'
			]);
		}
		$user->verification_code = null;
		$user->save();
		return response()->json([
			'code' => 200,
			'message' => 'Email has been verified',
			'status' => 'success'
		]);
	}

	/**
	 * It returns the authenticated user
	 * 
	 * @return The user object
	 */
	public function me()
	{
		return [
			'code' => 200,
			'status' => 'success',
			'user' => auth()->user()
		];
	}

	/**
	 * It logs the user out
	 * 
	 * @return An array with a code, status, and message.
	 */
	public function logout()
	{
		auth()->logout();

		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Successfully logged out'
		];
	}

	/**
	 * It takes the token from the request, and then returns a new token
	 * 
	 * @return The token is being returned.
	 */
	public function refresh()
	{
		return $this->respondWithToken(auth()->refresh());
	}

	/**
	 * It returns an array with the access token, token type, and expiration time
	 * 
	 * @param token The JWT token that will be used for authentication.
	 * 
	 * @return 'access_token' => ,
	 * 				'token_type' => 'bearer',
	 * 				'expires_in' => auth()->factory()->getTTL() * 60
	 */
	public function respondWithToken($token)
	{
		$user = auth()->user();
		$user->token = $token;
		return [
			'code' => 200,
			'status' => 'success',
			'data' => [
				'user' => $user
			]
		];
	}
}
