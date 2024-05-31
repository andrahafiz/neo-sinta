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
            $table->timestamp('date');
            $table->tinyInteger('status')->nullable();
            $table->string('pembahasan')();
            $table->string('catatan')->nullable();
            $table->string('title');
            $table->foreignId('pic');
            $table->string('saran')->nullable();
            $table->string('Dok_Per_Sem_Proyek');
            $table->foreignId('mahasiswas_id');

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
