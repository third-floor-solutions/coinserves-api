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
            $table->bigIncrements('id');
            $table->string('wallet_type')->default('bitcoin');
            $table->string('wallet_address')->unique();
            $table->string('status')->default('Unverified');
            $table->unsignedMediumInteger('initial_tx')->default(0);
            $table->unsignedMediumInteger('cnsrv_n_tx')->default(0);
            $table->unsignedMediumInteger('trees')->default(0);
            $table->string('user_id');
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
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
