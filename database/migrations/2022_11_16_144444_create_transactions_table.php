<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('coinFrom');
            $table->unsignedBigInteger('coinTo');
            $table->double('amountFrom');
            $table->double('amountTo');
            $table->double('rate');
            $table->string('wallet');
            $table->string('email')->nullable();
            $table->string('status')->default('pending');
            $table->string('hash')->unique();
            $table->string('type')->default('buy');
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('coinFrom')->references('id')->on('coins');
            $table->foreign('coinTo')->references('id')->on('coins');
            $table->index(['user_id', 'coinFrom', 'coinTo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
