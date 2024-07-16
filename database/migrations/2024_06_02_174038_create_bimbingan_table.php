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
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id();
            $table->string('pembahasan');
            $table->tinyInteger('catatan')->nullable();
            $table->timestamp('tanggal_bimbingan');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('mahasiswas_id');
            $table->foreignId('dosen_pembimbing1');
            $table->foreignId('dosen_pembimbing2');
            $table->tinyInteger('status')->nullable();
            $table->integer('bimbingaable_id');
            $table->string('bimbingaable_type');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mahasiswas_id')
                ->references('id')
                ->on('mahasiswas')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            $table->foreign('dosen_pembimbing1')
                ->references('id')
                ->on('lecture')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            $table->foreign('dosen_pembimbing2')
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
        Schema::dropIfExists('bimbingan');
    }
};
