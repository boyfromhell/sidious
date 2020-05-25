<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFrozenOptionAccount extends Migration
{
    public function up()
    {
        Schema::table("accounts", function (Blueprint $table) {
            $table->boolean("isFrozen")->default(false);
        });
    }

    public function down()
    {
        Schema::table("accounts", function (Blueprint $table) {
            $table->dropColumn("frozen");
        });
    }
}
