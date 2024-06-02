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
        Schema::create('seminar_pra_proposal', function (Blueprint $table) {
            $table->id();

            $table->timestamp('date');
            $table->string('title');
            $table->tinyInteger('status')->nullable();
            $table->foreignId('pic');
            $table->timestamp('proposed_at')->nullable();
            $table->timestamp('in_review_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->string('draf_pra_pro');
            $table->string('pra_pro_ppt');
            $table->string('dok_persetujuan_pra_pro');
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
        Schema::dropIfExists('seminar_pra_proposal');
    }
};
