<?php

namespace App\Services\Admin;

use App\Models\Coin;
use App\Models\CoinWallet;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;

class CoinService
{

	/**
	 * It returns a paginated list of coins
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code and data.
	 */
	public function index($request)
	{
		$per_page = $request->per_page ?? 10;
		$coins = Coin::orderBy('id', 'desc')->paginate($per_page);
		return [
			'code' => 200,
			'data' => $coins,
		];
	}

	/**
	 * > This function returns a 200 status code and the data of the coin
	 * 
	 * @param coin The coin's symbol.
	 * 
	 * @return An array with a code and data key.
	 */
	public function show($coin)
	{
		return [
			'code' => 200,
			'data' => $coin,
		];
	}

	/**
	 * It creates a new coin
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code and data.
	 */
	public function store($request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'symbol' => 'required|string|max:5',
			'fee' => 'required|numeric',
			'reserve' => 'required|numeric',
			'min_amount' => 'required|numeric',
			'max_amount' => 'required|numeric'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'errors' => $validator->errors(),
			];
		}

		if (empty($request->exchange_rate)) {
			$rate = @file_get_contents("https://www.binance.com/api/v3/depth?symbol=" . strtoupper($request->symbol) . "USDT&limit=1");
			$rate = json_decode($rate);
			$rate = $rate->bids[0][0];
			$request->merge(['exchange_rate' => $rate]);
		}

		$coin = Coin::create([
			'name' => $request->name,
			'symbol' => strtolower($request->symbol),
			'exchange_rate' => $request->exchange_rate,
			'fee' => $request->fee,
			'reserve' => $request->reserve,
			'min_amount' => $request->min_amount,
			'max_amount' => $request->max_amount,
		]);
		return [
			'code' => 200,
			'message' => 'Coin created successfully',
			'data' => $coin,
		];
	}

	/**
	 * It updates a coin
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code and data.
	 */
	public function update($request, $coin)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'symbol' => 'required|string|max:5',
			'fee' => 'required|numeric',
			'reserve' => 'required|numeric',
			'min_amount' => 'required|numeric',
			'max_amount' => 'required|numeric'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'errors' => $validator->errors(),
			];
		}
		$coin->update([
			'name' => $request->name,
			'symbol' => strtolower($request->symbol),
			'exchange_rate' => $request->exchange_rate,
			'fee' => $request->fee,
			'reserve' => $request->reserve,
			'min_amount' => $request->min_amount,
			'max_amount' => $request->max_amount,
		]);
		return [
			'code' => 200,
			'message' => 'Coin updated successfully',
			'data' => $coin,
		];
	}

	/**
	 * It deletes a coin
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code and data.
	 */
	public function destroy($coin)
	{
		CoinWallet::where('coin_id', $coin->id)->delete();
		Transaction::where('coinFrom', $coin->id)->orWhere('coinTo', $coin->id)->delete();
		$coin->delete();
		return [
			'code' => 200,
			'message' => 'Coin deleted successfully',
		];
	}

	/**
	 * It activates a coin
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code and data.
	 */
	public function active($coin)
	{
		Coin::where('id', $coin->id)->update(['is_active' => !$coin->is_active]);
		return [
			'code' => 200,
			'message' => 'Coin status updated successfully',
		];
	}
}
