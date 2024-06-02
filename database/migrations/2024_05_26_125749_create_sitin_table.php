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
        Schema::create('sitin', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('duration')->nullable();
            $table->string('check_in_proof')->nullable();
            $table->string('check_out_proof')->nullable();
            $table->string('check_out_document')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('mahasiswas_id');
            $table->foreignId('approval_by')->nullable();;
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('sitin');
    }
};
