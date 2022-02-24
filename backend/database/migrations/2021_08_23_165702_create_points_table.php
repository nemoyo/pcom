<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->comment('ポイントID');
            $table->text('name')->comment('タイトル');
            $table->integer('point')->comment('ポイント数');
            $table->text('comment')->comment('備考')->nullable();
            $table->text('explanation')->comment('補足')->nullable();
            $table->timestamps();
            $table->primary('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points');
    }
}
