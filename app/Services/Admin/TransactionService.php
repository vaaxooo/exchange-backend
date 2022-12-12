<?php

namespace App\Services\Admin;

use App\Models\Transaction;

class TransactionService
{

	/**
	 * It fetches all transactions from the database, and returns them in a paginated format
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function index($request)
	{
		$per_page = $request->per_page ?? 15;
		$transactions = Transaction::with('user')->with('coinFrom')->with('coinTo')->orderBy('created_at', 'desc')->paginate($per_page);
		return [
			'code' => 200,
			'message' => 'Transactions fetched successfully.',
			'data' => $transactions
		];
	}

	/**
	 * It fetches a transaction from the database, and returns it.
	 * 
	 * @param transaction The transaction model instance that we want to show.
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function show($transaction)
	{
		$transaction = Transaction::with('user')->with('coinFrom')->with('coinTo')->find($transaction->id);
		return [
			'code' => 200,
			'message' => 'Transaction fetched successfully.',
			'data' => $transaction
		];
	}

	/**
	 * It updates a transaction in the database.
	 * 
	 * @param request The request object
	 * @param transaction The transaction model
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function update($request, $transaction)
	{
		$transaction->update($request->all());
		return [
			'code' => 200,
			'message' => 'Transaction updated successfully.',
			'data' => $transaction
		];
	}

	/**
	 * It deletes the transaction and returns a 200 response with a message and the deleted transaction
	 * 
	 * @param transaction The transaction object that was created.
	 * 
	 * @return The response is being returned as an array.
	 */
	public function destroy($transaction)
	{
		$transaction->delete();
		return [
			'code' => 200,
			'message' => 'Transaction deleted successfully.',
			'data' => $transaction
		];
	}

	/**
	 * It updates the status of a transaction
	 * 
	 * @param request The request object
	 * @param transaction The transaction object
	 * 
	 * @return an array with the following keys:
	 * - code
	 * - message
	 * - data
	 */
	public function setStatus($request, $transaction)
	{
		$transaction->update([
			'status' => $request->status,
			'comment' => $request->comment
		]);
		return [
			'code' => 200,
			'message' => 'Transaction status updated successfully.',
			'data' => $transaction
		];
	}
}
