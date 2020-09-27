<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('standings', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('player_id');
      $table->integer('rank')->nullable();
      $table->integer('points')->nullable();
      $table->integer('match_wins')->nullable();
      $table->integer('match_losses')->nullable();
      $table->integer('match_draws')->nullable();
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
    Schema::dropIfExists('standings');
  }
}
