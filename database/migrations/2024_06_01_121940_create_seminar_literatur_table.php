<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seminar_literatur', function (Blueprint $table) {
            $table->id();
            $table->string('status', 20)->nullable();
            $table->timestamp('date')->nullable();
            $table->foreignId('pic')->nullable();
            $table->string('check_in_ppt');
            $table->text('check_in_literatur');
            $table->text('note')->nullable();
            $table->foreignId('mahasiswas_id');
            $table->float('nilai_seminar_literatur');
            $table->timestamp('tanggal_seminar_literatur');
            $table->foreignId('approval_by')->nullable();
            $table->timestamps();

            $table->foreign('pic')
                ->references('id')
                ->on('lecture')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            $table->foreign('mahasiswas_id')
                ->references('id')
                ->on('mahasiswas')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            $table->foreign('approval_by')
                ->references('id')
                ->on('lecture')
                ->restrictOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seminar_literatur');
    }
};
