<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Coin;
use Illuminate\Support\Facades\Validator;
use App\Models\CoinWallet;

class UserService
{

	/**
	 * It returns a paginated list of users
	 * 
	 * @param request The request object.
	 * 
	 * @return An array with a code and data.
	 */
	public function index($request)
	{
		$per_page = $request->per_page ?? 10;
		$users = User::orderBy('id', 'desc')->paginate($per_page);
		return [
			'code' => 200,
			'data' => $users,
		];
	}

	/**
	 * "Return a 200 response with the given user."
	 * 
	 * The function is a little more complicated than that, but not much. It's a function that returns a
	 * response
	 * 
	 * @param user The user object that was returned from the database.
	 * 
	 * @return An array with two keys, code and data.
	 */
	public function show($user)
	{
		return [
			'code' => 200,
			'data' => $user,
		];
	}

	/**
	 * It takes a user object and a request object, validates the request, and updates the user object with
	 * the request data
	 * 
	 * @param request The request object
	 * @param user The user object that you want to update.
	 * 
	 * @return An array with a code and data.
	 */
	public function update($request, $user)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
			'fio' => 'required|string|max:255',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'errors' => $validator->errors(),
			];
		}
		$user->email = $request->email;
		$user->fio = $request->fio;
		$user->save();
		return [
			'code' => 200,
			'data' => $user,
		];
	}

	/**
	 * > Delete the user from the database
	 * 
	 * @param user The user object that is being deleted.
	 * 
	 * @return The user object is being returned.
	 */
	public function destroy($user)
	{
		$user->delete();
		return [
			'code' => 200,
			'data' => $user,
		];
	}

	/**
	 * > It takes a user object, toggles the ban status, and returns a response
	 * 
	 * @param user The user object that you want to ban.
	 * 
	 * @return An array with a code and a message.
	 */
	/* It takes a user object, toggles the ban status, and returns a response. */
	public function boolBan($user)
	{
		$user->ban = !$user->ban;
		$user->save();
		return [
			'code' => 200,
			'message' => 'Ban status updated',
		];
	}

	/**
	 * It takes a request and a user, validates the request, updates the user's password, and returns a
	 * response
	 * 
	 * @param request The request object
	 * @param user The user object that you want to change the password for.
	 * 
	 * @return An array with a code and a message.
	 */
	public function changePassword($request, $user)
	{
		$validator = Validator::make($request->all(), [
			'password' => 'required|string|min:8',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'errors' => $validator->errors(),
			];
		}
		$user->password = bcrypt($request->password);
		$user->save();
		return [
			'code' => 200,
			'message' => 'Password updated',
		];
	}

	/**
	 * It takes a request and a user, validates the request, sets the user's balance to the amount in the
	 * request, and returns the user
	 * 
	 * @param request The request object
	 * @param user The user object
	 * 
	 * @return An array with a code and data.
	 */
	public function setBalance($request, $user)
	{
		$validator = Validator::make($request->all(), [
			'amount' => 'required|numeric',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'data' => $validator->errors(),
			];
		}
		$user->balance = $request->amount;
		$user->save();
		return [
			'code' => 200,
			'message' => 'Balance updated',
		];
	}

	/**
	 * It gets all the coins, then gets all the wallets for the user, then returns the wallets
	 * 
	 * @param request The request object
	 * @param user The user object that is currently logged in.
	 * 
	 * @return An array of wallets.
	 */
	public function getWallets($request, $user)
	{
		$coins = Coin::get();
		$wallets = [];
		foreach ($coins as $coin) {
			$wallet = $user->wallets()->with('coin')->where('coin_id', $coin->id)->first();
			if ($wallet) {
				$wallets[] = $wallet;
			} else {
				$wallets[] = [
					'coin_id' => $coin->id,
					'coin' => $coin,
					'balance' => 0,
				];
			}
		}
		return [
			'code' => 200,
			'data' => $wallets,
		];
	}

	/**
	 * It takes a user and a request, validates the request, and then updates the user's wallet balance
	 * 
	 * @param request The request object
	 * @param user The user object
	 * 
	 * @return An array with a code and a message.
	 */
	public function setWalletBalance($request, $user)
	{
		$validator = Validator::make($request->all(), [
			'coin_id' => 'required|numeric',
			'amount' => 'required|numeric',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'data' => $validator->errors(),
			];
		}
		$wallet = $user->wallets()->where('coin_id', $request->coin_id)->first();
		if ($wallet) {
			$wallet->balance = $request->amount;
			$wallet->save();
		} else {
			$wallet = new CoinWallet;
			$wallet->user_id = $user->id;
			$wallet->coin_id = $request->coin_id;
			$wallet->balance = $request->amount;
			$wallet->save();
		}
		return [
			'code' => 200,
			'message' => 'Balance updated',
		];
	}
}
