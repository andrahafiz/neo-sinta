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
        Schema::create('seminar_proyek', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status', 20)->nullable();
            $table->timestamp('date');
            $table->foreignId('pic')->nullable();
            $table->string('dok_per_sem_proyek');
            $table->string('dok_per_sem_proyek');
            $table->foreignId('mahasiswas_id');
            $table->timestamp('proposed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seminar_proyek');
    }
};
