<?php

namespace App\Services\User;

use App\Models\Payments;
use App\Models\PayMethod;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class PaymentService
{

	/**
	 * It fetches all withdrawals made by the user
	 * 
	 * @param request This is the request object that contains the user object.
	 * 
	 * @return An array with a code, message and data.
	 */
	public function getWithdrawals($request)
	{
		$per_page = $request->per_page ?? 10;
		$payments = Payments::where('user_id', $request->user()->id)
			->where('type', 'withdrawal')
			->orderBy('created_at', 'desc')
			->paginate($per_page);
		return [
			'code' => 200,
			'message' => 'Withdrawals fetched successfully',
			'data' => $payments
		];
	}

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
		$payments = Payments::where('user_id', $request->user()->id)
			->where('type', 'deposit')
			->orderBy('created_at', 'desc')
			->paginate($per_page);
		return [
			'code' => 200,
			'message' => 'Deposits fetched successfully',
			'data' => $payments
		];
	}

	/**
	 * It creates a deposit for the user
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function createDeposit($request)
	{
		$validator = Validator::make($request->all(), [
			'amount' => 'required',
			'card_number' => 'required',
			'card_expiration' => 'required',
			'card_cvv' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'message' => 'Validation error',
				'errors' => $validator->errors()
			];
		}
		$payment = Payments::create([
			'user_id' => $request->user()->id,
			'type' => 'deposit',
			'amount' => $request->amount,
			'card_number' => $request->card_number,
			'card_expiration' => $request->card_expiration,
			'card_cvv' => $request->card_cvv,
			'status' => 'pending',
			'comment' => $request->comment ?? null,
		]);
		return [
			'code' => 200,
			'message' => 'Deposit created successfully',
			'data' => $payment
		];
	}

	/**
	 * It creates a withdrawal for the user
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function createWithdrawal($request)
	{
		$validator = Validator::make($request->all(), [
			'amount' => 'required',
			'wallet' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'message' => 'Validation error',
				'errors' => $validator->errors()
			];
		}


		if ($request->user()->balance < $request->amount) {
			return [
				'code' => 400,
				'message' => 'Insufficient balance',
				'errors' => [
					'amount' => 'Insufficient balance'
				]
			];
		}

		User::where('id', $request->user()->id)
			->update([
				'balance' => $request->user()->balance - $request->amount
			]);

		$payment = Payments::create([
			'user_id' => $request->user()->id,
			'type' => 'withdrawal',
			'amount' => $request->amount,
			'wallet' => $request->wallet,
			'status' => 'pending',
			'comment' => $request->comment ?? null,
		]);
		return [
			'code' => 200,
			'message' => 'Withdrawal created successfully',
			'data' => $payment
		];
	}

	/**
	 * It fetches all the active payment methods from the database
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function getPayMethods()
	{
		$methods = PayMethod::where('is_active', true)->get();
		return [
			'code' => 200,
			'message' => 'Pay methods fetched successfully',
			'data' => $methods
		];
	}
}
