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
            $table->tinyInteger('status')->nullable();
            $table->timestamp('date');
            $table->foreignId('pic');
            $table->string('check_in_ppt');
            $table->string('check_in_literatur');
            $table->foreignId('mahasiswas_id');
            $table->foreignId('approval_by');

            

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
