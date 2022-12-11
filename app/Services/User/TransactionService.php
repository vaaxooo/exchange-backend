<?php

namespace App\Services\User;

use App\Models\Coin;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;

class TransactionService
{

	/**
	 * It fetches all transactions from the database and returns them in a paginated format
	 * 
	 * @return An array with a code, message, and data.
	 */
	public function getAllTransactions()
	{
		$transactions = Transaction::with('coinFrom', 'coinTo')->orderBy('id', 'desc')->limit(4)->get();
		return [
			'code' => 200,
			'data' => $transactions
		];
	}

	/**
	 * It creates a transaction
	 * 
	 * @param request The request object
	 * 
	 * @return An array with the following keys:
	 */
	public function createTransaction($request)
	{
		$validator = Validator::make($request->all(), [
			'coinFrom' => 'required',
			'coinTo' => 'required',
			'amountFrom' => 'required',
			'wallet' => 'required'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'message' => 'Bad request',
				'data' => $validator->errors()
			];
		}
		$coinFrom = Coin::where('id', $request->coinFrom)->first();
		$coinTo = Coin::where('id', $request->coinTo)->first();
		if ($coinFrom->id == $coinTo->id) {
			return [
				'code' => 400,
				'message' => 'You cannot convert the same coin to itself',
			];
		}
		if ($request->amountFrom < $coinFrom->min_amount) {
			return [
				'code' => 400,
				'message' => 'The minimum amount you can convert is ' . $coinFrom->min_amount . ' ' . $coinFrom->name,
			];
		}
		if ($request->amountFrom > $coinFrom->max_amount) {
			return [
				'code' => 400,
				'message' => 'The maximum amount you can convert is ' . $coinFrom->max_amount . ' ' . $coinFrom->name,
			];
		}

		$rate = $coinFrom->exchange_rate / $coinTo->exchange_rate;
		$amountTo = $request->amountFrom * $rate;
		$hash = md5($request->coinFrom . $request->coinTo . $request->amountFrom . $request->wallet . time());
		$transaction = Transaction::create([
			'coinFrom' => $request->coinFrom,
			'coinTo' => $request->coinTo,
			'amountFrom' => $request->amountFrom,
			'amountTo' => $amountTo,
			'rate' => $coinTo->exchange_rate,
			'wallet' => $request->wallet,
			'email' => $request->email,
			'hash' => $hash
		]);

		$message = "Новая заявка!\n";
		$message .= "Отдаёт: {$request->amountFrom} ({$coinFrom->symbol})\n";
		$message .= "Получает: {$amountTo} ({$coinTo->symbol})\n";
		$message .= "Кошелёк получателя: {$request->wallet}\n";
		$message .= "Почта: {$request->email}\n";
		$message .= "Hash: {$hash}\n";
		Telegram::sendMessage([
			'chat_id' => config('app.TELEGRAM_CHAT_ID'),
			'text' => $message
		]);

		return [
			'code' => 200,
			'message' => 'Transaction created successfully',
			'data' => $transaction
		];
	}

	/**
	 * It gets a transaction by its hash
	 * 
	 * @param hash The transaction hash
	 * 
	 * @return An array with a code and data or code and message.
	 */
	public function getTransactionByHash($hash)
	{
		$transaction = Transaction::with('coinFrom', 'coinTo')->where('hash', $hash)->first();
		if ($transaction) {
			return [
				'code' => 200,
				'data' => $transaction
			];
		}
		return [
			'code' => 404,
			'message' => 'Transaction not found'
		];
	}


	/**
	 * It updates the transaction status to `cancelled` and returns a success message
	 * 
	 * @param request The request object
	 * @param transaction The transaction object.
	 * 
	 * @return An array with a code and a message.
	 */
	public function cancel($transaction)
	{
		$transaction = Transaction::where('hash', $transaction)->first();
		if (!$transaction) {
			return [
				'code' => 404,
				'message' => 'Transaction not found'
			];
		}
		Transaction::where('hash', $transaction->hash)->update(['status' => 'declined']);
		return [
			'code' => 200,
			'message' => 'Transaction status updated successfully.',
		];
	}
}
