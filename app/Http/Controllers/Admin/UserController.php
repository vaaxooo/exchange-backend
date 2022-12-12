<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Admin\UserService;

class UserController extends Controller
{

	private $users;

	public function __construct()
	{
		$this->users = new UserService();
	}

	/**
	 * > It returns a JSON response of all users
	 * 
	 * @param Request request The request object.
	 * 
	 * @return A JSON response of all the users.
	 */
	public function index(Request $request)
	{
		return response()->json($this->users->index($request));
	}

	public function store(Request $request)
	{
		//
	}

	/**
	 * > If the user is authenticated, return the user as JSON
	 * 
	 * @param User user The user model instance that we want to show.
	 * 
	 * @return A JSON response of the user.
	 */
	public function show(User $user)
	{
		return response()->json($this->users->show($user));
	}

	/**
	 * > Update a user
	 * 
	 * @param Request request The request object
	 * @param User user The user model
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function update(Request $request, User $user)
	{
		return response()->json($this->users->update($request, $user));
	}

	/**
	 * > The `destroy` function deletes a user from the database
	 * 
	 * @param User user The user object that is being deleted.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function destroy(User $user)
	{
		return response()->json($this->users->destroy($user));
	}

	/**
	 * > This function will return a JSON response of the active method in the User model
	 * 
	 * @param User user The user object that you want to activate.
	 * 
	 * @return A JSON response.
	 */
	public function boolBan(User $user)
	{
		return response()->json($this->users->boolBan($user));
	}

	/**
	 * It takes a request and a user, and returns a json response of the result of the changePassword
	 * function in the Users class
	 * 
	 * @param Request request The request object
	 * @param User user The user object that is being updated.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function changePassword(Request $request, User $user)
	{
		return response()->json($this->users->changePassword($request, $user));
	}

	/**
	 * > This function sets the amount of a user
	 * 
	 * @param Request request The request object
	 * @param User user The user object that is being passed in from the route.
	 * 
	 * @return A JSON response.
	 */
	public function setBalance(Request $request, User $user)
	{
		return response()->json($this->users->setBalance($request, $user));
	}

	/**
	 * It returns a JSON response of the wallets for the user
	 * 
	 * @param Request request The request object
	 * @param User user The user object that is currently logged in.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getWallets(Request $request, User $user)
	{
		return response()->json($this->users->getWallets($request, $user));
	}

	/**
	 * It sets the wallet balance of a user.
	 * 
	 * @param Request request The request object
	 * @param User user The user object that is being passed in from the route.
	 * 
	 * @return A JSON response.
	 */
	public function setWalletBalance(Request $request, User $user)
	{
		return response()->json($this->users->setWalletBalance($request, $user));
	}
}
