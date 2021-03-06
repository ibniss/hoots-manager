<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePairingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pairings', function (Blueprint $table) {
      $table->uuid('id');
      $table->unsignedBigInteger('round_id');
      $table->unsignedBigInteger('player_1_id')->nullable();
      $table->unsignedBigInteger('player_2_id')->nullable();
      $table->string('result');
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
    Schema::dropIfExists('pairings');
  }
}
