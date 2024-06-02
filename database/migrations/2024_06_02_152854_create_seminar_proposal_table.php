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
        Schema::create('seminar_proposal', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->tinyInteger('status')->nullable();
            $table->string('pembahasan');
            $table->string('catatan')->nullable();
            $table->string('title');
            $table->foreignId('pic');
            $table->string('saran')->nullable();
            $table->string('draf_pro');
            $table->string('pro_ppt');
            $table->string('dok_persetujuan_pro');
            $table->foreignId('mahasiswas_id');
            $table->foreignId('approval_by');

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
        Schema::dropIfExists('seminar_proposal');
    }
};
