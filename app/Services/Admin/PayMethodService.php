<?php

namespace App\Services\Admin;

use App\Models\PayMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayMethodService
{

	/**
	 * It returns a list of all the payment methods
	 * 
	 * @return An array with a code and data.
	 */
	public function index()
	{
		$methods = PayMethod::get();
		return [
			'code' => 200,
			'data' => $methods
		];
	}

	/**
	 * It creates a new payment method
	 * 
	 * @param Request request The request object.
	 * 
	 * @return An array with a code and data.
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string',
			'address' => 'required|string'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'errors' => $validator->errors()
			];
		}

		$paymethod = PayMethod::create([
			'name' => $request->name,
			'address' => $request->address
		]);
		return [
			'code' => 200,
			'message' => 'Payment method created successfully',
			'data' => $paymethod
		];
	}

	/**
	 * It deletes the payment method from the database
	 * 
	 * @param paymethod The payment method object
	 * 
	 * @return The code 200 and the data "Payment method deleted successfully"
	 */
	public function destroy($paymethod)
	{
		$paymethod->delete();
		return [
			'code' => 200,
			'message' => 'Payment method deleted successfully'
		];
	}

	/**
	 * It takes a payment method, toggles its active status, and then returns a success message
	 * 
	 * @param paymethod The payment method object
	 * 
	 * @return An array with a code and data.
	 */
	public function active($paymethod)
	{
		PayMethod::where('id', $paymethod->id)->update(['is_active' => !$paymethod->is_active]);
		return [
			'code' => 200,
			'message' => 'Payment method activated successfully'
		];
	}
}
