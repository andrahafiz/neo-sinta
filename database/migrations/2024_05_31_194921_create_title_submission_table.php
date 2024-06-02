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
            $table->string('status', 20)->nullable();
            $table->foreignId('pic')->nullable();
            $table->foreignId('mahasiswas_id');
            $table->timestamp('proposed_at')->nullable();
            $table->timestamp('in_review_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('declined_at')->nullable();
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
