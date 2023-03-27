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
        Schema::create('subject_trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('trainer_id')
                ->constrained('trainer_profiles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_trainers');
    }
};
