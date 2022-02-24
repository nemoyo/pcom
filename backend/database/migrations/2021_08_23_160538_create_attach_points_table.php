<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attach_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ask_year')->length(4)->comment('申請年');
            $table->integer('ask_month')->length(2)->comment('申請月');
            $table->date('ask_date')->comment('申請年月日');
            $table->foreignId('user_group_id')->constrained('groups')->comment('組織ID');
            $table->string('user_id')->comment('ユーザーID');
            $table->integer('total_point')->comment('合計ポイント');
            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attach_points');
    }
}
