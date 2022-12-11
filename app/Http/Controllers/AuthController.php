<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

use App\Services\AuthService;

class AuthController extends Controller
{
	private $authService;
	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->authService = new AuthService();
		$this->middleware('auth:api', ['except' => ['login', 'register', 'sendMail', 'verifyCode', 'resetPassword', 'verifyCodeEmail', 'verifyEmail']]);
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login()
	{
		return $this->authService->login();
	}

	/**
	 * It creates a new user and returns a token
	 * 
	 * @return The token is being returned.
	 */
	public function register()
	{
		return $this->authService->register();
	}


	/**
	 * It returns a JSON response of the result of the sendMail() function in the AuthService class.
	 * 
	 * @return The response is being returned as a json object.
	 */

	public function sendMail()
	{
		return response()->json($this->authService->sendMail());
	}


	/**
	 * It returns a json response of the result of the verifyCode() function in the AuthService class
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function verifyCode()
	{
		return response()->json($this->authService->verifyCode());
	}

	/**
	 * It checks if the user exists, if the code is correct, and if it is, it changes the password and
	 * clears the recovery code
	 */
	public function resetPassword()
	{
		return response()->json($this->authService->resetPassword());
	}

	/**
	 * It returns a JSON response of the result of the `sendVerifyEmail` function in the `AuthService`
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function verifyCodeEmail()
	{
		return response()->json($this->authService->verifyCodeEmail());
	}

	/**
	 * > The `verifyEmail()` function returns a JSON response of the `verify()` function in the
	 * `AuthService` class
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function verifyEmail()
	{
		return response()->json($this->authService->verifyEmail());
	}


	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me()
	{
		return response()->json($this->authService->me());
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout()
	{
		return response()->json($this->authService->logout());
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh()
	{
		return $this->authService->refresh();
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json($this->authService->respondWithToken($token));
	}
}
