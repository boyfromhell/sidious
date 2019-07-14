<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedMediumInteger("account_from")->nullable()->index();
            $table->unsignedBigInteger("amount")->default(0);
            $table->boolean("isCredit")->default(false);
            $table->string("motivation");
            $table->timestamps();
            $table->unsignedMediumInteger("account_to")->index();
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
}
