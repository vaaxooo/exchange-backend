<?php

namespace App\Services\Admin;

use App\Models\Payments;
use Illuminate\Support\Facades\Validator;


class PaymentService
{

	/**
	 * It fetches all the deposits made by the user
	 * 
	 * @param request This is the request object that is passed to the controller method.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function getDeposits($request)
	{
		$per_page = $request->per_page ?? 10;
		$payments = Payments::with('user')->orderBy('created_at', 'desc')->paginate($per_page);
		return [
			'code' => 200,
			'message' => 'Deposits fetched successfully',
			'data' => $payments
		];
	}

	/**
	 * It returns a JSON response with a 200 status code, a message, and the payment data
	 * 
	 * @param payment The payment object that was fetched from the database.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function show($payment)
	{
		$payment = Payments::with('user')->find($payment->id);
		return [
			'code' => 200,
			'message' => 'Payment fetched successfully',
			'data' => $payment
		];
	}

	/**
	 * It validates the request, and updates the status of the payment.
	 * 
	 * @param request The request object
	 * @param payment The payment object
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function setStatus($request, $payment)
	{
		$validator = Validator::make($request->all(), [
			'status' => 'required'
		]);

		if ($validator->fails()) {
			return [
				'code' => 400,
				'message' => 'Validation error',
				'data' => $validator->errors()
			];
		}

		$payment->status = $request->status;
		$payment->comment = !empty($request->comment) ? $request->comment : $payment->comment;
		$payment->save();

		return [
			'code' => 200,
			'message' => 'Payment status updated successfully',
			'data' => $payment
		];
	}
}
