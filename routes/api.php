<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/bot/get-updates', function () {
    $updates = Telegram::getUpdates();
    return (json_encode($updates));
});

Route::group(['middleware' => ['api']], function ($route) {
    /* This is a route group that is prefixed with 'auth'. */
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', '\App\Http\Controllers\AuthController@login');
        Route::post('register', '\App\Http\Controllers\AuthController@register');
        Route::post('logout', '\App\Http\Controllers\AuthController@logout');
        Route::post('refresh', '\App\Http\Controllers\AuthController@refresh');
        Route::post('me', '\App\Http\Controllers\AuthController@me');

        # EMAIL VERIFICATION
        Route::post('verification/send', '\App\Http\Controllers\AuthController@verifyCodeEmail');
        Route::post('verification/check', '\App\Http\Controllers\AuthController@verifyEmail');


        # PASSWORD RESET
        Route::post('password/check', '\App\Http\Controllers\AuthController@verifyCode');
        Route::post('password/send', '\App\Http\Controllers\AuthController@sendMail');
        Route::post('password/reset', '\App\Http\Controllers\AuthController@resetPassword');
    });

    # ADMIN API ROUTES
    Route::group(['middleware' => ['admin']], function () {
        Route::group(['prefix' => 'admin'], function () {
            # COINS
            Route::get('coins/{coin}/active', '\App\Http\Controllers\Admin\CoinController@active');
            Route::resource('coins', '\App\Http\Controllers\Admin\CoinController');

            # USERS
            Route::get('users/{user}/wallets', '\App\Http\Controllers\Admin\UserController@getWallets');
            Route::post('users/{user}/wallets/set-balance', '\App\Http\Controllers\Admin\UserController@setWalletBalance');

            Route::get('users/{user}/ban', '\App\Http\Controllers\Admin\UserController@boolBan');
            Route::post('users/{user}/balance', '\App\Http\Controllers\Admin\UserController@setBalance');
            Route::post('users/{user}/change-password', '\App\Http\Controllers\Admin\UserController@changePassword');
            Route::resource('users', '\App\Http\Controllers\Admin\UserController');

            # TRANSACTIONS
            Route::post('transactions/{transaction}/set-status', '\App\Http\Controllers\Admin\TransactionController@setStatus');
            Route::resource('transactions', '\App\Http\Controllers\Admin\TransactionController');

            # PAGES
            Route::get('pages/{page}/active', '\App\Http\Controllers\Admin\PageController@active');
            Route::resource('pages', '\App\Http\Controllers\Admin\PageController');

            # PAYMENTS
            Route::post('payments/{payment}/set-status', '\App\Http\Controllers\Admin\PaymentController@setStatus');
            Route::get('payments', '\App\Http\Controllers\Admin\PaymentController@getDeposits');
            Route::get('payments/{payment}', '\App\Http\Controllers\Admin\PaymentController@show');

            # CONTACTS
            Route::resource('contacts', '\App\Http\Controllers\Admin\ContactController');

            # PAY METHODS
            Route::get('pay-methods/{payMethod}/active', '\App\Http\Controllers\Admin\PayMethodController@active');
            Route::resource('pay-methods', '\App\Http\Controllers\Admin\PayMethodController');
        });
    });


    # USER API ROUTES
    Route::group(['middleware' => ['ban', 'email']], function () {
        Route::get(
            'transactions',
            '\App\Http\Controllers\User\TransactionController@getTransactions'
        );
        Route::post('transactions', '\App\Http\Controllers\User\TransactionController@createTransaction');

        Route::post('user/change-password', '\App\Http\Controllers\User\UserController@changePassword');

        # PAYMENTS
        Route::get('payments/withdrawals', '\App\Http\Controllers\User\PaymentController@getWithdrawals');
        Route::get('payments/deposits', '\App\Http\Controllers\User\PaymentController@getDeposits');
        Route::post('payments/withdrawal', '\App\Http\Controllers\User\PaymentController@createWithdrawal');
        Route::post('payments/deposit', '\App\Http\Controllers\User\PaymentController@createDeposit');

        Route::get('payments/methods', '\App\Http\Controllers\User\PaymentController@getPayMethods');

        # WALLETS
        Route::get('wallets', '\App\Http\Controllers\User\CoinController@wallets');
    });
});


Route::get('coins', '\App\Http\Controllers\User\CoinController@getCoins');
Route::get('coins/{id}', '\App\Http\Controllers\User\CoinController@getCoin');


Route::get('transactions/{hash}/cancel', '\App\Http\Controllers\User\TransactionController@cancel');
Route::get('transactions/{hash}', '\App\Http\Controllers\User\TransactionController@getTransactionByHash');

Route::get('pages', '\App\Http\Controllers\User\PageController@index');
Route::get('pages/{slug}', '\App\Http\Controllers\User\PageController@show');

Route::get('get-contacts', '\App\Http\Controllers\User\PageController@getContacts');
