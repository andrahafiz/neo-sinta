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
            $table->string('title');
            $table->string('status', 20)->nullable();
            $table->timestamp('date');
            $table->foreignId('pic')->nullable();
            $table->string('draf_pro');
            $table->string('pro_ppt');
            $table->string('dok_persetujuan_pro');
            $table->float('nilai_seminar_proposal')->nullable();
            $table->timestamp('tanggal_seminar_proposal')->nullable();
            $table->foreignId('mahasiswas_id');
            $table->text('note')->nullable();
            $table->foreignId('approval_by')->nullable();
            $table->timestamp('proposed_at')->nullable();
            $table->timestamp('in_review_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('declined_at')->nullable();

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
        Schema::dropIfExists('seminar_proposal');
    }
};
