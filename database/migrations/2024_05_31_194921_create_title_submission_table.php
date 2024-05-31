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
        Schema::create('title_submission', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('status')->nullable();
            $table->timestamp('date');
            $table->foreignId('pic');
            $table->foreignId('mahasiswas_id');
            $table->foreignId('pembimbing_1');
            $table->foreignId('pembimbing_2');
            $table->string('dok_pengajuan_judul');
            $table->string('konsentrasi_ilmu');
            

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mahasiswas_id')
                  ->references('id')
                  ->on('mahasiswas')
                  ->restrictOnUpdate()
                  ->restrictOnDelete();

            $table->foreign('pic')
                ->references('id')
                ->on('lecture')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            $table->foreign('pembimbing_1')
            ->references('id')
            ->on('lecture')
            ->restrictOnUpdate()
            ->restrictOnDelete();

            $table->foreign('pembimbing_2')
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
        Schema::dropIfExists('title_submission');
    }
};
