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

	public function changePassword(Request $request)
	{
		return response()->json($this->users->changePassword($request));
	}
}
