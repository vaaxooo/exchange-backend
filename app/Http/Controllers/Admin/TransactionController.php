<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Admin\TransactionService;

class TransactionController extends Controller
{

	private $transactions;

	public function __construct()
	{
		$this->transactions = new TransactionService();
	}

	/**
	 * > It returns a JSON response of all transactions
	 * 
	 * @param Request request The request object.
	 * 
	 * @return A JSON response of all the transactions.
	 */
	public function index(Request $request)
	{
		return response()->json($this->transactions->index($request));
	}

	public function store(Request $request)
	{
		//
	}

	/**
	 * > If the transaction is authenticated, return the transaction as JSON
	 * 
	 * @param Transaction transaction The transaction model instance that we want to show.
	 * 
	 * @return A JSON response of the transaction.
	 */
	public function show(Transaction $transaction)
	{
		return response()->json($this->transactions->show($transaction));
	}

	/**
	 * > Update a transaction
	 * 
	 * @param Request request The request object
	 * @param Transaction transaction The transaction model
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function update(Request $request, Transaction $transaction)
	{
		return response()->json($this->transactions->update($request, $transaction));
	}

	/**
	 * > The `destroy` function deletes a transaction from the database
	 * 
	 * @param Transaction transaction The transaction model instance.
	 */
	public function destroy(Transaction $transaction)
	{
		//
	}

	/**
	 * It sets the status of a transaction.
	 * 
	 * @param Request request The request object
	 * @param Transaction transaction The transaction object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function setStatus(Request $request, Transaction $transaction)
	{
		return response()->json($this->transactions->setStatus($request, $transaction));
	}
}
