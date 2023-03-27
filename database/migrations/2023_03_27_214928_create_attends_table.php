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
        Schema::create('attends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('history_id')
                ->constrained('histories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('date_id')
                ->constrained('dates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('lesson_number');
            $table->boolean('state');
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
        Schema::dropIfExists('attends');
    }
};
