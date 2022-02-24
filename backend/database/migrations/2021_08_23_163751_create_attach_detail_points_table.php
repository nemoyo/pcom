<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachDetailPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attach_detail_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attach_point_id')->constrained('attach_points')->comment('attach_points_id');
            $table->integer('point_id')->comment('付与ポイントID');
            $table->string('attach_date',8)->comment('付与日');
            $table->text('reason')->comment('理由')->nullable();
            $table->text('name')->comment('タイトル');
            $table->text('comment')->comment('備考')->nullable();
            $table->integer('point')->comment('付与ポイント');
            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attach_detail_points');
    }
}
