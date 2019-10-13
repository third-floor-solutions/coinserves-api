<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockchainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blockchains', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('wallet_type')->default('bitcoin');
            $table->string('wallet_address')->unique();
            $table->unsignedMediumInteger('initial_tx')->default(0);
            $table->unsignedMediumInteger('cnsrv_n_tx')->default(0);
            $table->unsignedMediumInteger('trees')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blockchains');
    }
}
