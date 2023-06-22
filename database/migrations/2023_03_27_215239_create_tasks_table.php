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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')
                ->constrained('trainer_profiles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('course_id')
                ->constrained('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('lesson_number');
            $table->text('the_question');
            $table->json('options');
            $table->integer('answer');
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
        Schema::dropIfExists('tasks');
    }
};
