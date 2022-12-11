<?php

namespace App\Services\Admin;

use App\Models\Coin;
use Illuminate\Support\Facades\Validator;

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
			'exchage_rate' => 'required|numeric',
			'network_fee' => 'required|numeric',
			'fee' => 'required|numeric',
			'reserve' => 'required|numeric',
			'min_amount' => 'required|numeric',
			'max_amount' => 'required|numeric'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'data' => $validator->errors(),
			];
		}
		$coin = Coin::create([
			'name' => $request->name,
			'symbol' => strtolower($request->symbol),
			'exchage_rate' => $request->exchage_rate,
			'network_fee' => $request->network_fee,
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
			'exchage_rate' => 'required|numeric',
			'network_fee' => 'required|numeric',
			'fee' => 'required|numeric',
			'reserve' => 'required|numeric',
			'min_amount' => 'required|numeric',
			'max_amount' => 'required|numeric'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'data' => $validator->errors(),
			];
		}
		$coin->update([
			'name' => $request->name,
			'symbol' => strtolower($request->symbol),
			'exchage_rate' => $request->exchage_rate,
			'network_fee' => $request->network_fee,
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
		$coin->active = !$coin->active;
		$coin->save();
		return [
			'code' => 200,
			'message' => 'Coin status updated successfully',
		];
	}
}
