<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\TransactionService;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * > This function returns all transactions
     * 
     * @return A JSON response with a code, message, and data.
     */
    public function getTransactions()
    {
        return response()->json($this->transactionService->getAllTransactions());
    }

    /**
     * > This function creates a new transaction
     * 
     * @return A JSON response with a code, message, and data.
     */
    public function createTransaction(Request $request)
    {
        return response()->json($this->transactionService->createTransaction($request));
    }

    /**
     * > This function returns a transaction
     * 
     * @param hash The hash of the transaction
     * 
     * @return A JSON response with a code, message, and data.
     */
    public function getTransactionByHash($hash)
    {
        return response()->json($this->transactionService->getTransactionByHash($hash));
    }

    /**
     * > This function returns a transaction
     * 
     * @param id The id of the transaction
     * 
     * @return A JSON response with a code, message, and data.
     */
    public function cancel($transaction)
    {
        return response()->json($this->transactionService->cancel($transaction));
    }
}
