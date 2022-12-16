<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
	private $users;

	public function __construct()
	{
		$this->users = new UserService();
	}

	/**
	 * It takes a request, passes it to the `Users` model, and returns the response as JSON
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function changePassword(Request $request)
	{
		return response()->json($this->users->changePassword($request));
	}

	/**
	 * It takes a request, passes it to the `Users` class, and returns the response as JSON
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function sendVerification(Request $request)
	{
		return response()->json($this->users->sendVerification($request));
	}
}
