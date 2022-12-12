<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\CoinService;
use Illuminate\Http\Request;
use App\Models\Coin;

class CoinController extends Controller
{
	private $coinService;

	/**
	 * The constructor function is called when the class is instantiated. It is used to initialize the
	 * class
	 */
	public function __construct()
	{
		$this->coinService = new CoinService();
	}

	/**
	 * It returns a JSON response of the coins from the coinService.
	 * 
	 * @return A JSON response.
	 */
	public function getCoins()
	{
		return response()->json($this->coinService->getCoins());
	}

	/**
	 * It returns a JSON response of the coinService->getCoin() function.
	 * 
	 * @param id The id of the coin you want to get.
	 * 
	 * @return A JSON response.
	 */
	public function getCoin($id)
	{
		return response()->json($this->coinService->getCoin($id));
	}

	/**
	 * It returns a JSON response of the wallets array from the CoinService class
	 * 
	 * @return The wallets method is returning a json response of the wallets method in the coinService
	 * class.
	 */
	public function wallets()
	{
		return response()->json($this->coinService->wallets());
	}
}
