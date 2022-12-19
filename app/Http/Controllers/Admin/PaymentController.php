<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use App\Services\Admin\PaymentService;

class PaymentController extends Controller
{
	private $payments;

	public function __construct()
	{
		$this->payments = new PaymentService();
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
	 * > It returns a JSON response of the payment object
	 * 
	 * @param Payments payment The payment object that was created.
	 * 
	 * @return A JSON object of the payment.
	 */
	public function show(Payments $payment)
	{
		return response()->json($this->payments->show($payment));
	}

	/**
	 * > The `destroy` function deletes a payment from the database
	 * 
	 * @param Payments payment The payment object that was passed in from the route.
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function destroy(Payments $payment)
	{
		return response()->json($this->payments->destroy($payment));
	}

	/**
	 * It takes a request and a payment, and returns a json response of the result of the setStatus
	 * function in the Payments class.
	 * 
	 * @param Request request The request object
	 * @param Payments payment The payment object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function setStatus(Request $request, Payments $payment)
	{
		return response()->json($this->payments->setStatus($request, $payment));
	}
}
