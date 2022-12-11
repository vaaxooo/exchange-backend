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
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol');
            $table->float('exchange_rate')->default(0);
            $table->float('fee')->default(0);
            $table->string('min_amount')->default(0);
            $table->string('max_amount')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('wallet');
            $table->string('reserve')->nullable();
            $table->timestamps();
        });

        Schema::table('coins', function (Blueprint $table) {
            $table->index(['symbol', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
};
