<?php

namespace App\Services\User;

use App\Models\Coin;
use App\Models\CoinWallet;

class CoinService
{

	/**
	 * It returns an array of coins that are active and ordered by symbol.
	 * 
	 * @return An array with a code and data.
	 */
	public function getCoins()
	{
		$coins = Coin::select('id', 'name', 'symbol', 'exchange_rate', 'min_amount', 'max_amount', 'fee')->where('is_active', true)->orderBy('symbol', 'asc')->get();
		return [
			'code' => 200,
			'data' => $coins
		];
	}


	/**
	 * It returns a coin if it exists, otherwise it returns a 404 error
	 * 
	 * @param id The id of the coin you want to get.
	 * 
	 * @return An array with a code and data.
	 */
	public function getCoin($id)
	{
		$coin = Coin::where('id', $id)->where('is_active', true)->first();
		if (!$coin) {
			return [
				'code' => 404,
				'message' => 'Coin not found.',
				'data' => []
			];
		}
		return [
			'code' => 200,
			'data' => $coin
		];
	}

	/**
	 * It returns a list of wallets for the currently logged in user
	 * 
	 * @return A collection of wallets with the coin data.
	 */
	public function wallets()
	{
		$per_page = request()->per_page ? request()->per_page : 10;
		$wallets = CoinWallet::where('user_id', auth()->user()->id)->with('coin')->paginate($per_page);
		return [
			'code' => 200,
			'data' => $wallets
		];
	}
}
