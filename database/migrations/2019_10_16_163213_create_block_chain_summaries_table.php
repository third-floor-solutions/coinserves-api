<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockChainSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blockchain_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedMediumInteger('trees')->default(0);
            $table->unsignedMediumInteger('tress_to_plant')->default(0);
            $table->unsignedMediumInteger('trees_planted')->default(0);
            $table->unsignedMediumInteger('trees_funded')->default(0);
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
        Schema::dropIfExists('block_chain_summaries');
    }
}
