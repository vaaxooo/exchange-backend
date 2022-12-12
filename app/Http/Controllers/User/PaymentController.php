<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use App\Services\User\PaymentService;

class PaymentController extends Controller
{
	private $payments;

	public function __construct()
	{
		$this->payments = new PaymentService();
	}

	/**
	 * It returns a JSON response of the result of the `getWithdrawals` method of the `Payments` class
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function getWithdrawals(Request $request)
	{
		return response()->json($this->payments->getWithdrawals($request));
	}

	/**
	 * It returns a JSON response of the results of the `getDeposits` function in the `Payments` class
	 * 
	 * @param Request request The request object
	 * 
	 * @return A JSON response of the deposits.
	 */
	public function getDeposits(Request $request)
	{
		return response()->json($this->payments->getDeposits($request));
	}

	/**
	 * It takes a request, passes it to the `Payments` class, and returns the response as JSON
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function createDeposit(Request $request)
	{
		return response()->json($this->payments->createDeposit($request));
	}

	/**
	 * It takes a request, passes it to the payments class, and returns the response as JSON
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a json object.
	 */
	public function createWithdrawal(Request $request)
	{
		return response()->json($this->payments->createWithdrawal($request));
	}
}
